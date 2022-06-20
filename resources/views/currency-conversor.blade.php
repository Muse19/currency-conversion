@extends ('layouts.main')

@section('title', 'Currency Converter')

@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link href="https://cdn.jsdelivr.net/gh/mobius1/selectr@latest/dist/selectr.min.css" rel="stylesheet" type="text/css">
    <style>
        .inputs-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr 50px 1fr;
            grid-gap: 1rem;
            margin-bottom: 1.5rem;

        }

        button#shuffle {
            background-color: #fff;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            padding: 0.85rem;
            border: 1px solid #ccc;
            margin-top: 20px;
        }

        .amount-wrapper {
            position: relative;
        }

        .amount-wrapper #amount {
            padding-left: 20px;
        }

        .amount-wrapper #symbol {
            position: absolute;
            left: 9px;
            top: 17px;
        }

        .result-wrapper {
            display: flex;
            justify-content: space-between;
        }

        .result-wrapper .actions {
            text-align: right;
        }

        #convertedAmount {
            font-size: 3rem;
            margin-bottom: 0.8rem;
        }

        #fromAmount {
            font-size: 1rem;
            margin-bottom: 0.8rem;
            color: #5c667b;
        }

        #fromRate {
            margin-bottom: 0.3rem;
        }

        #lastUpdated {
            text-align: right;
            display: block;
            margin-top: 1rem;
        }

        .custom-option {
            display: flex;
            align-items: center;
            gap: .4rem;

        }

        .custom-option img {
            min-width: 33px;
        }

        .custom-option img:before {
            content: ' ';
            display: block;
            position: absolute;
            height: 24px;
            width: 33px;
            background: rgb(216, 216, 216);
            border-radius: 3px
        }

        .selectr-container {
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            margin-bottom: 10px;
            font-size: 1.1rem;
            box-shadow: 0px 3px 15px rgb(0 17 51 / 5%);
            margin-top: .3rem;
        }

        .selectr-container .selectr-selected {
            border: none;
            padding: 9px 28px 8px 14px
        }

        .selectr-container .selectr-placeholder {
            color: #6c7a86;
            padding: 1px 0 2px 0;
        }

        .selectr-options-container {
            border: 1px solid #ccc;
        }

        #results {
            transition: opacity .2s ease-in-out
        }

        .actions {
            display: flex;
            align-items: flex-end;
        }
    </style>
@endsection


@section('content')
    <form id="form-conversion">


        <div class="inputs-wrapper">
            <div class="input-group">
                <label for="amount">Amount</label>
                <div class="amount-wrapper">
                    <input type="number" name="amount" id="amount" step="any">
                    <span id="amountError" style="color: red"> </span>
                    <div id="symbol">$</div>
                </div>

            </div>

            <div class="input-group">
                <label for="from">From</label>
                <select name="from" id="from"></select>
                <span id="fromError" style="color: red"> </span>
            </div>

            <div>
                <button type="button" id="shuffle">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 17" aria-hidden="true"
                        class="miscellany___StyledIconSwap-sc-1r08bla-1 fZJuOo">
                        <path fill="#539eff" fill-rule="evenodd"
                            d="M11.726 1.273l2.387 2.394H.667V5h13.446l-2.386 2.393.94.94 4-4-4-4-.94.94zM.666 12.333l4 4 .94-.94L3.22 13h13.447v-1.333H3.22l2.386-2.394-.94-.94-4 4z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>


            <div class="input-group">
                <label for="to">To</label>
                <select name="to" id="to"></select>
                <span id="toError" style="color: red"> </span>
            </div>
        </div>

        <div class="result-wrapper">

            <section id="results" style="visibility: hidden">
                <h6 id="fromAmount"></h6>
                <h2 id="convertedAmount"></h2>

                <br>

                <p id="fromRate"></p>
                <p id="toRate"></p>
            </section>

            <div class="actions">
                <button type="submit" class="btn btn-primary" id="button-conversion">Convert</button>
            </div>
        </div>

        <small id="lastUpdated"></small>
    </form>
@endsection


@section('script')
    <script src="https://cdn.jsdelivr.net/gh/mobius1/selectr@latest/dist/selectr.min.js" type="text/javascript"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        @if (isset($error))
            (function() {
                launchToast("{{ $error }}")
            })()
        @endif
        const currencies = @json($currencies)

        const amount = document.getElementById('amount');
        const from = document.getElementById('from');
        const to = document.getElementById('to')
        const resultsBox = document.getElementById('results')
        const shuffleBtn = document.getElementById('shuffle')
        const form = document.getElementById('form-conversion');
        const submitBtn = document.getElementById('button-conversion')
        const url = "{{ url('/convert') }}?"
        let isLoading = false;


        function renderer(data) {
            if (!data) return ''
            var text = data.text;
            var template = [
                `<div class="custom-option"><img src="/flags/${data.value}.svg" height="24" />`,
                data
                .text,
                '</div>'
            ];
            return template.join('');
        }
        let fromSelectr;
        let toSelectr;
        (function() {
            if (!currencies.length) return;
            const currenciesMapped = currencies.map(({
                currencyName,
                id
            }) => ({
                value: id,
                text: `${id} - ${currencyName}`
            }))
            fromSelectr = new Selectr('#from', {
                data: currenciesMapped,
                renderOption: renderer,
                renderSelection: renderer,
                placeholder: "Select a currency",
            });

            fromSelectr.setValue('USD')

            toSelectr = new Selectr('#to', {
                data: currenciesMapped,
                renderOption: renderer,
                renderSelection: renderer,
                placeholder: "Select a currency",
            });

            toSelectr.setValue('EUR')
        })();

        // Functions
        const isResultsBoxVisible = () => resultsBox.style.visibility === 'unset'

        function updateSymbol(id) {
            const currency = currencies.find(currency => currency.id == id)
            const symbol = document.getElementById('symbol')
            symbol.innerText = currency.currencySymbol || ' '
            amount.style.paddingLeft = `${symbol.offsetWidth + 11 }px`
        }

        function getCurrencyName(currency) {
            const currencyName = currency.split('-')[1]
            return currencyName.trim() || ''
        }

        function debounce(callback, wait) {
            let timerId;
            return (...args) => {
                clearTimeout(timerId);
                timerId = setTimeout(() => {
                    callback(...args);
                }, wait);
            };
        }

        function convert() {
            if (isLoading) {
                return
            }

            if (!amount.value) {
                const span = document.getElementById(`amountError`)
                span.innerText = 'Please enter an amount'
                setTimeout(() => {
                    span.innerText = ''
                }, 3000);
                return
            }

            if (Number(amount.value) <= 0) {
                const span = document.getElementById(`amountError`)
                span.innerText = 'Enter an amount greater than 0'
                setTimeout(() => {
                    span.innerText = ''
                }, 3000);
                return
            }

            const data = {
                amount: amount.value,
                from: fromSelectr.getValue(),
                to: toSelectr.getValue()
            };
            console.log({
                data
            })
            const params = new URLSearchParams(data)

            submitBtn.disabled = true;

            isLoading = true;
            resultsBox.style.opacity = 0;
            fetch(url + params, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function({
                    data,
                    status,
                    message
                }) {
                    if (status === 429) {
                        window.location = '/limit-reached'
                        return
                    }

                    if (status === 422) {
                        launchToast()
                        for (const field in message) {
                            const span = document.getElementById(`${field}Error`)
                            span.innerText = message[field][0]
                            setTimeout(() => {
                                span.innerText = ''
                            }, 3000)
                        }
                        submitBtn.disabled = false;
                        return
                    }

                    if (status !== 200) {
                        launchToast(data.message)
                        submitBtn.disabled = false;
                        return
                    }


                    const fromAmountText = `${amount.value} ${getCurrencyName(from.selectedOptions[0].label)}  =`
                    document.getElementById('fromAmount').innerHTML = fromAmountText;

                    const convertedAmountText =
                        `${data.converted_amount} ${getCurrencyName(to.selectedOptions[0].label)}`
                    document.getElementById('convertedAmount').innerHTML = convertedAmountText;

                    const fromRateText = `1 ${from.value} = ${data.conversion_rate_from} ${to.value}`
                    document.getElementById('fromRate').innerHTML = fromRateText;

                    const toRateText = `1 ${to.value} = ${data.conversion_rate_to} ${from.value}`
                    document.getElementById('toRate').innerHTML = toRateText;

                    const fromCurrencyName = getCurrencyName(from.selectedOptions[0].label)
                    const toCurrencyName = getCurrencyName(to.selectedOptions[0].label)
                    const lastUpdatedText =
                        `<a href="https://en.wikipedia.org/w/index.php?search=${fromCurrencyName}" target="_blank">${fromCurrencyName}</a> to <a href="https://en.wikipedia.org/w/index.php?search=${toCurrencyName}" target="_blank">${toCurrencyName}</a> conversion — Last updated ${data.conversion_rate_date} UTC`
                    document.getElementById('lastUpdated').innerHTML = lastUpdatedText;

                    resultsBox.style.visibility = 'unset'

                })
                .finally(function() {
                    isLoading = false;
                    submitBtn.disabled = false;
                    resultsBox.style.opacity = 1;
                });
        }

        function launchToast(message = 'Something went wrong') {
            Toastify({
                text: message,
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                stopOnFocus: true,
                style: {
                    background: "red",
                },
            }).showToast();
        }


        form.addEventListener('submit', function(e) {
            e.preventDefault();
            convert()
        });

        shuffleBtn.addEventListener('click', function(e) {
            const tmpValue = from.value
            console.log(tmpValue, from.value, to.value)
            fromSelectr.setValue(to.value)
            console.log(tmpValue, from.value, to.value)
            toSelectr.setValue(tmpValue)
            console.log(tmpValue, from.value, to.value)
            updateSymbol(from.value)
        })

        amount.addEventListener('input', debounce(function(e) {
            if (isResultsBoxVisible()) convert()
        }, 1000));
    </script>
@endsection
