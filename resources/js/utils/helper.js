export default {
    install(Vue) {
        Vue.prototype.$h = {
            formatCurrency(number, separator) {
                if (number) {
                    let splitArray = number.toString().split('.')
                    let decimalPart = ''
                    if (splitArray.length > 1) {
                        number = splitArray[0]
                        if (splitArray[1] != '00') {
                            decimalPart = '.' + splitArray[1]
                        }
                    }
                    let formattedNumber = number.toString().replace(/\D/g, "");
                    let rest = formattedNumber.length % 3;
                    let currency = formattedNumber.substr(0, rest);
                    let thousand = formattedNumber.substr(rest).match(/\d{3}/g);

                    if (thousand) {
                        separator = rest ? separator ? separator : "," : "";
                        currency += separator + thousand.join(",");
                    }

                    return currency + decimalPart;
                }

                return "0";
            },
            formatPrice(price, currency) {
                if (price == null) return ""
                if (currency) return `${this.formatCurrency(price, ',')} ${currency}`
                return this.formatCurrency(price, ',')
            },
            kNumber(num, digits = 1) {
                if (isNaN(num) || num <= 0) {
                    return isNaN(num) ? "NaN" : 0
                }
                const lookup = [
                    {value: 1, symbol: ""},
                    {value: 1e3, symbol: "k"},
                    {value: 1e6, symbol: "M"},
                    {value: 1e9, symbol: "G"},
                    {value: 1e12, symbol: "T"},
                    {value: 1e15, symbol: "P"},
                    {value: 1e18, symbol: "E"}
                ]
                const rx = /\.0+$|(\.[0-9]*[1-9])0+$/;
                var item = lookup.slice().reverse().find(function (item) {
                    return num >= item.value
                });
                return (num / item.value).toFixed(digits).replace(rx, "$1") + item.symbol
            },
            async createFile(file) {
                var reader = new FileReader()
                return new Promise((resole, reject) => {
                    reader.onload = (e) => {
                        resole(e.target.result)
                    }
                    reader.readAsDataURL(file)
                })
            }
        };
    }
};
