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

$('#predict-submit').click(function() {
    var odd = parseInt($('#odd').val());
    var maxOdd = odd + 1;
    var minMatch = parseInt($('#min-match').val()) == 0 ? 11 : parseInt($('#min-match').val());
    var maxMatch = parseInt($('#max-match').val()) == 0 ? 12 : parseInt($('#max-match').val());

    console.log(odd);
    console.log(maxOdd);
    console.log(minMatch);
    console.log(maxMatch);

    if (odd == 0) {
        console.log("Odd numeric issues");
        return;
    }

    if (minMatch == 0) {
        console.log("mianMatch numeric issues");
        return;
    }

    if (maxMatch == 0) {
        console.log("Max numeric issues");
        return;
    }

    if (minMatch >= maxMatch) {
        console.log("mianMatch issues");
        return;
    }

    const url = '/predict-zoom?min_odd='+odd + '&max_odd=' + maxOdd + '&min_match=' + minMatch + '&max_match=' + maxMatch;


    $('.my-game').remove();
    $('#empty').css('display', 'none');
    $('#predict-submit').text('Submiting...');
    $('#predict-submit').css('cursor', 'not-allowed');
    $('#predict-submit').css('color', '#222');
    $('#predict-submit').prop('disabled', true);
    $.ajax(url, {
    type: 'GET',  success: function(result) {
        $('#predict-submit').text('Submit');
        $('#predict-submit').css('cursor', 'pointer');
        $('#predict-submit').prop('disabled', false);
        $('#predict-submit').css('color', '#fff');

        if (result.success) {
            const options = result.data.options;
            var $html = '<table class="uk-table uk-table-divider uk-table-middle uk-table-justify my-game">';
            $html +='<h4 class="my-game">Total Odds: <b>' + result.data.odd + '</b></h4>';
            $html +='<h4 class="my-game">Accuracy: <b>' + result.data.probability + '</b></h4>';
            $html +='<thead>' + '<tr>';
            $html += '<th>Fixture</th>' + '<th class="uk-text-success">Outcome</th>' + '<th class="uk-text-emphasis">Out.Probability</th>';
            $html += '<th class="uk-text-emphasis">Odd</th>';
            $html += '</tr></thead><tbody>';
            options.forEach(function(option) {
                $html+= '<tr>' + '<td>' + option.fixture + '</td>';
                $html+='<td>' + option.name + '</td>';
                $html+='<td>' + (parseFloat(option.stat) * 100) + '%</td>';
                $html+='<td>' + option.odd + '</td>';
                $html+='<tr>';
            });

            $html +='</tbody></table>';
            $('#t-wrapper').append($html);
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