$('#submit').click(function() {
    var competition = $('#competition').val();

    if (!isNumeric(competition) || competition == 0) {
        return;
    }

    $('.my-game').remove();
    $('#empty').css('display', 'none');
    $('#submit').text('Submiting...');
    $('#submit').css('cursor', 'not-allowed');
    $('#submit').css('color', '#222');
    $('#submit').prop('disabled', true);
    $.ajax('/get-data?competition_id='+competition, {
    type: 'GET',  success: function(result) {
        $('#submit').text('Submit');
        $('#submit').css('cursor', 'pointer');
        $('#submit').prop('disabled', false);
        $('#submit').css('color', '#fff');

        if (result.success) {
            var gamesWithProfit = result.data.result.won;

            if (gamesWithProfit.length == 0) {
                $('#empty').css('display', 'block');
            } else {
                gamesWithProfit.forEach(function(game) {
                    var $html = '<table class="uk-table uk-table-divider uk-table-middle uk-table-justify my-game">';

                    $('#t-wrapper').append($html);
                });
            }

        }
        console.log(result);
   }});
});

function getUrl(platform, urls) {
    return urls[platform];
}
function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
    try {
      decimalCount = Math.abs(decimalCount);
      decimalCount = isNaN(decimalCount) ? 2 : decimalCount;
  
      const negativeSign = amount < 0 ? "-" : "";
  
      let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
      let j = (i.length > 3) ? i.length % 3 : 0;
  
      return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
    } catch (e) {
      console.log(e)
    }
  };
function isNumeric(str) {
    if (typeof str != "string") return false // we only process strings!  
    return !isNaN(str) && // use type coercion to parse the _entirety_ of the string (`parseFloat` alone does not do this)...
           !isNaN(parseFloat(str)) // ...and ensure strings of whitespace fail
  }