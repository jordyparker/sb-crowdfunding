<?php

namespace App\Http\Controllers\API\V1;

use App\Enums\CommonStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCampaignRequest;
use App\Http\Requests\DonateRequest;
use App\Http\Resources\DonationCampaignResource;
use App\Http\Resources\DonationResource;
use App\Models\DonationCampaign;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class DonationCampaignController extends Controller
{
    /**
     * Returns a list of donation campaigns.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Response
    {
        $campaigns = DonationCampaign::query()
            ->with(['category', 'creator', 'receiver'])
            ->withCount('donations')
            ->withSum('donations', 'base_amount')
            ->when($search = request('search'), function ($q) use ($search) {
                return $q->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                });
            })
            ->when($status = request('status'), function ($q) use ($status) {
                return match ($status) {
                    'closed' => $q->whereNotNull('closed_at'),
                    'open' => $q->where(function ($q) {
                        $q->whereNull('closed_at')
                            ->orWhere('ends_at', '>', now());
                    }),
                    default => $q
                };
            })
            ->when($category = request('category'), function ($q) use ($category) {
                return $q->where('category_id', $category);
            })
            ->when($user = request('created_by'), function ($q) use ($user) {
                return $q->where('creator_id', $user)
                    ->where('creator_type', User::class);
            })
            ->orderBy(request('created_at', 10), request('dir', 'desc'))
            ->paginate(request('page_size', 10))
            ->through(function ($campaign) {
                return DonationCampaignResource::make($campaign);
            });

        return response([
            'data' => $campaigns,
            'code' => 'CAMPAIGNS_RETRIEVED',
            'message' => __('Campaigns successfully retrieved')
        ], 200);
    }

    /**
     * Create a donation campaign
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCampaignRequest $request)
    {
        $user = $request->user('api');

        $campaign = DonationCampaign::query()
            ->create([
                'name' => $request->name,
                'description' => $request->description,
                'slug' => generateSlug(new DonationCampaign, $request->name),
                'target_amount' => $request->target_amount,
                'min_donation_amount' => $request->min_donation_amount,
                'number_of_participants' => $request->number_of_participants,
                'category_id' => $request->category_id,
                'currency' => $request->currency,
                'ends_at' => $request->ends_at,
                'creator_id' => $user->id,
                'creator_type' => get_class($user),
                'receiver_id' => $user->id,
                'receiver_type' => get_class($user)
            ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')
                ->store("donation-campaings/{$campaign->id}");

            $campaign->update(['image' => $path]);
        }

        return response([
            'data' => DonationCampaignResource::make($campaign),
            'code' => 'CAMPAIGN_CREATED',
            'message' => __('Donation campaign successfully created!')
        ], 201);
    }

    /**
     * Donate to an ongoing campaign
     *
     * @param \Illuminate\Http\Request $request
     * @param int $campaignId
     * @return \Illuminate\Http\Response
     */
    public function donate(DonateRequest $request, int $campaignId): Response
    {
        $campaign = DonationCampaign::query()
            ->findOrFail($campaignId);

        DB::beginTransaction();

        try {
            $user = $request->user('api');

            $transaction = Transaction::query()
                ->create([
                    'transaction_id' => uniqid('TID'),
                    'payer_id' => $user ? $user->id : null,
                    'payer_type' => $user ? get_class($user) : null,
                    'payment_number' => $request->payment_number,
                    'item_type' => get_class($campaign),
                    'item_id' => $campaign->id,
                    'amount' => $request->amount,
                    'base_amount' => convertAmount(
                        $request->currency,
                        $campaign->getTargetCurrency(),
                        $request->amount
                    ),
                    'currency' => $request->currency,
                    'base_currency' => $campaign->getTargetCurrency(),
                    'payment_method' => $request->payment_method,
                    'status' => CommonStatus::COMPLETE->value
                ]);

            $donation = $campaign->collectDonation(
                $transaction,
                $request->comments
            );

            DB::commit();

            return response([
                'data' => DonationResource::make($donation->load('transaction')),
                'code' => 'DONATION_COLLECTED',
                'message' => __('Donation successfully collected for the campaign!')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Returns the details of a campaign
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(string $id): Response
    {
        $campaign = DonationCampaign::query()
            ->with(['category', 'creator', 'receiver'])
            ->withCount('donations')
            ->withSum('donations', 'base_amount')
            ->findOrFail($id);

        return response([
            'data' => DonationCampaignResource::make($campaign),
            'code' => 'CAMPAIGN_RETRIEVED',
            'message' => __('Campaign successfully retrieved')
        ], 200);
    }

    /**
     * Returns the list of donations for a particular campaign
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function donations(int $id): Response
    {
        $campaign = DonationCampaign::query()->findOrFail($id);

        $donations = $campaign->donations()
            ->with('donator')
            ->when($user = request('donated_by'), function ($q) use ($user) {
                return $q->where('donator_id', $user)
                    ->where('donator_type', User::class);
            })
            ->orderBy(request('order_by', 'created_at'), request('dir', 'desc'))
            ->paginate(request('page_size', 10))
            ->through(function ($donation) {
                return DonationResource::make($donation);
            });

        return response([
            'data' => $donations,
            'campaign' => DonationCampaignResource::make($campaign),
            'code' => 'DONATIONS_RETRIEVED',
            'message' => __('Donations successfully retrieved')
        ], 200);
    }
}
