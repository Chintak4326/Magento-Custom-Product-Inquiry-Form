require([
    'jquery'
    ],function($){

      $(document).ready(function() {
        var input = document.querySelector("#phone");
        var iti = window.intlTelInput(input, {
          // initialCountry: "auto",
          // allowDropdown: false,
          // autoHideDialCode: false,
          // autoPlaceholder: "off",
          // dropdownContainer: document.body,
          // excludeCountries: ["us"],
          // formatOnDisplay: false,
          // geoIpLookup: function(callback) {
          //   $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
          //     var countryCode = (resp && resp.country) ? resp.country : "";
          //     callback(countryCode);
          //   });
          // },
          // hiddenInput: "full_number",
          // initialCountry: "auto",
          // localizedCountries: { 'de': 'Deutschland' },
          // nationalMode: false,
          // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
          // placeholderNumberType: "MOBILE",
          // preferredCountries: ['cn', 'jp'],
          // separateDialCode: true,
          utilsScript: "build/js/utils.js",
        });

        input.addEventListener("countrychange", function() {
            // console.log(iti.getSelectedCountryData());
            $('#country_code').val(iti.getSelectedCountryData().dialCode);
            // console.log($('#country_code').val(iti.getSelectedCountryData().dialCode));
        });
    });
});