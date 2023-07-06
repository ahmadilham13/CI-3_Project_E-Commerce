function createSlug() {
    let title = $('#title').val()
    $('#slug').val(stringToSlug(title))
}

function stringToSlug (str) {
    str = str.replace(/^\s+|\s+$/g, ''); // trim
    str = str.toLowerCase();
  
    // remove accents, swap ñ for n, etc
    var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
    var to   = "aaaaeeeeiiiioooouuuunc------";
    for (var i=0, l=from.length ; i<l ; i++) {
        str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
    }

    str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
        .replace(/\s+/g, '-') // collapse whitespace and replace by -
        .replace(/-+/g, '-'); // collapse dashes

    return str;
}

const reviewScore = document.querySelectorAll('.review-score');
reviewScore.forEach((score) => {
  const scoreValue = score.getAttribute('data-score');
  let scoreHtml = '';
  for (let i = 0; i < 5; i++) {
    if (i < (scoreValue-0.5)) {
      scoreHtml += `<div class="star-fill"></div>`;
    } else {
      scoreHtml += `<div class="star-empty"></div>`;
    }
  }
  score.innerHTML = scoreHtml;
});