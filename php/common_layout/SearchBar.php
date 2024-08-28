<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Search Bar</title>
    <link rel="stylesheet" href="../css/SearchBar.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function loadOptions(url, selector, manufacturerValue) {
            $.ajax({
                url: url + '?manufacturer=' + encodeURIComponent(manufacturerValue), 
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $(selector).empty(); 
                    $(selector).append($('<option>', { 
                        value: '',
                        text : 'Seleziona...' 
                    }));
                    data.forEach(function(item) {
                        $(selector).append($('<option>', { 
                            value: item.value,
                            text : item.text 
                        }));
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Errore nella chiamata AJAX:', status, error);
                }
            });
        }

        function generatePriceOptions() {
            var priceSelect = $('#price');
            priceSelect.append($('<option>', { 
                value: '',
                text : 'price<=(€)' 
            }));
            
            var increment = 10000;
            var currentPrice = 10000;
            
            while (currentPrice <= 10000000) {
                priceSelect.append($('<option>', { 
                    value: currentPrice,
                    text : currentPrice.toLocaleString('it-IT', { style: 'currency', currency: 'EUR' }) 
                }));

                if (currentPrice === 100000) {
                    increment *= 2;  
                }

                currentPrice += increment;
            }
        }

        $(document).ready(function() {
            loadOptions('./script/getManufacturers.php', '#manufacturer');

            $('#manufacturer').on('change', function() {
                var manufacturerValue = $(this).val();
                loadOptions('./script/getModels.php', '#model', manufacturerValue);
            });

            generatePriceOptions();

            var currentYear = new Date().getFullYear();
            var yearSelect = $('#year');
            yearSelect.append($('<option>', { 
                value: '',
                text : 'year' 
            }));
            for (var year = 1990; year <= currentYear; year++) {
                yearSelect.append($('<option>', { 
                    value: year,
                    text : year 
                }));
            }
        });
    </script>
</head>
<body>
                <div class="form-group">
                    <select name="manufacturer" id="manufacturer">
                        <option value="">manufacturer</option>
                    </select>
                </div>

                <div class="form-group">
                    <select name="model" id="model">
                        <option value="">model</option>
                    </select>
                </div>

                <div class="form-group">
                    <select name="price" id="price">
                    </select>
                </div>

                <div class="form-group">
                    <select name="year" id="year">
                    </select>
                </div>

                <div class="form-group">
                    <select name="fuel" id="fuel">
                        <option value="">fuel</option>
                        <option value="gasoline">gasoline</option>
                        <option value="diesel">diesel</option>
                        <option value="electric">electric</option>
                        <option value="hybrid">hybrid</option>
                    </select>
                </div>

                <div class="form-group">
                    <select name="gear" id="gear">
                        <option value="">gear</option>
                        <option value="automatic">automatic</option>
                        <option value="manual">manual</option>
                        <option value="semi-automatic">semi-automatic</option>
                    </select>
                </div>

                <div class="form-group">
                    <select name="color" id="color">
                        <option value="">color</option>
                        <option value="black" class="black">
                            <span class="color-option"></span> black
                        </option>
                        <option value="grey" class="grey">
                            <span class="color-option"></span> grey
                        </option>
                        <option value="white" class="white">
                            <span class="color-option"></span> white
                        </option>
                        <option value="red" class="red">
                            <span class="color-option"></span> red
                        </option>
                        <option value="green" class="green">
                            <span class="color-option"></span> green
                        </option>
                        <option value="orange" class="orange">
                            <span class="color-option"></span> orange
                        </option>
                    </select>
                </div>

                <button type="submit" class="results-button">search</button>


</body>
</html>
