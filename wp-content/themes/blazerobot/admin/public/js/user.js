document.addEventListener("DOMContentLoaded", () => {
  (function ($) {

    // Create button
    $('[data-name="blaze"] .acf-fields')
      .attr('style', 'display:flex;flex-wrap:wrap;')
      .append(`<button name="login-blaze" id="login-blaze" class="button button-primary">Verificar credenciais</button>`)

    // Call function on click
    $('body').on('click', '#login-blaze', (e) => {
      e.preventDefault();

      // clean errors
      $('.label-login').remove()

      // init loading
      $(e.currentTarget).addClass('loading')

      $.ajax({
        type: "POST",
        url: `http://${window.location.hostname + ajaxurl}`,
        data: {
          action: 'login_blaze',
          user: $('[data-name="email"] input').val(),
          pass: $('[data-name="password"] input').val()
        },
        success: ({ success, data }) => {
          console.log('s', success);
          if (success) {
            console.log('s', data);
            $('[data-name="token"] input').val(data.token)
            $('[data-name="wallet_id"] input').val(data.wallet_id)
            $('[data-name="blaze"] .acf-fields').append(`<p class="label-login -success">${data.msg}</p>`)
          } else {
            $('[data-name="blaze"] .acf-fields').append(`<p class="label-login -error">${data.msg}</p>`)
          }
        },
        error: (r) => {
          console.log('error', r);
        },
      }).always(function () {
        $(e.currentTarget).removeClass('loading')
      });
    });

    // Confirm correct credentials
    if ($('[data-name="token"] input').val() != "" && $('[data-name="wallet_id"] input').val() != "") {
      $('[data-name="blaze"] .acf-fields').append(`<p class="label-login -success">Credenciais corretas.</p>`)
    }


  })(jQuery);
})