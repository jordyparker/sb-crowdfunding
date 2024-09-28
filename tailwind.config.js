import defaultTheme from 'tailwindcss/defaultTheme'

export default {
    mode: 'jit',
    content: [
        './resources/**/*.{html,js,jsx,vue,php}',
    ],
    darkMode: 'false',
    theme: {
        zIndex: {
            '5': 5,
            '10': 10,
            '15': 15,
            '20': 20,
            '25': 25,
            '30': 30,
            '35': 35,
            '40': 40,
            '45': 45,
            '50': 50,
            '75': 75,
            '100': 100,
            '200': 200,
            '999': 999,
            '1000': 1000,
            '9999': 9999,
            'auto': 'auto',
        },
        extend: {
            fontFamily: {
                'poppins': ['"Poppins"', ...defaultTheme.fontFamily.sans],
            },
        },
    }
}

