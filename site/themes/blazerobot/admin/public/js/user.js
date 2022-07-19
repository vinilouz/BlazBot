const init = () => {

  let $ = jQuery.noConflict();

  const icon = `<?xml version="1.0" encoding="utf-8"?><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 460 460" xmlns:v="https://vecta.io/nano"><path d="M230 0C102.975 0 0 102.975 0 230s102.975 230 230 230 230-102.974 230-230S357.025 0 230 0zm38.333 377.36c0 8.676-7.034 15.71-15.71 15.71h-43.101c-8.676 0-15.71-7.034-15.71-15.71V202.477c0-8.676 7.033-15.71 15.71-15.71h43.101c8.676 0 15.71 7.033 15.71 15.71V377.36zM230 157c-21.539 0-39-17.461-39-39s17.461-39 39-39 39 17.461 39 39-17.461 39-39 39z"/></svg>`,
    token_field = acf.getField('field_62c7823aeb86a'),
    wallet_id = acf.getField('field_62c7843724502'),
    status_field = acf.getField('field_62c65d03a74bb'),
    license_field = acf.getField('field_62c8ce6bd90a0');

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
      url: `https://${window.location.hostname + ajaxurl}`,
      data: {
        action: 'login_blaze',
        user: $('[data-name="email"] input').val(),
        pass: $('[data-name="password"] input').val()
      },
      success: ({ success, data }) => {
        console.log('success', success);
        console.log('data', data);
        if (success) {
          token_field.val(data.token)
          wallet_id.val(data.wallet_id)
          $('[data-name="blaze"] .acf-fields').append(`<p class="label-login -success">${icon + data.msg}</p>`)
        } else {
          $('[data-name="blaze"] .acf-fields').append(`<p class="label-login -error">${icon + data.msg}</p>`)
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
  if (token_field.val() != "" && wallet_id.val() != "") {
    $('[data-name="blaze"] .acf-fields').append(`<p class="label-login -success">${icon}Credenciais corretas.</p>`)
  }

  // Validate on bot turn On
  if (token_field.val() == '' || wallet_id.val() == '' || license_field.val() != 1) {
    $(status_field).closest('.acf-field').addClass('not-allowed');
  }

  // Always block license field
  license_field.$input().closest('.acf-field').addClass('not-allowed');


  /**
   * Popup
   */
  $(status_field.$input()).on('change', () => {
    const value = status_field.val()
    if (token_field.val() == '' || wallet_id.val() == '') {
      openModal('sua conta não esta validada');
      $(document).on("closeCustomModal", function(e){$("#acf-field_62c65d03a74bb").trigger('click')});
    } else if (license_field.val() != 1) {
      openModal('sua licença não esta em dia');
      $(document).on("closeCustomModal", function(e){$("#acf-field_62c65d03a74bb").trigger('click')});
    } else if (value == 1) {
      openModal('Seu robô foi ligado, agora é só esperar que os sinais irão direto para sua conta')
    }
  });

  // Create modal
  let popupDOM = `
  <div id="custom-popup" class="popup">
    <div class="popup-wrapper">
      <span class="close">x</span>
      <p class="msg"></p>
    </div>
  </div>`
  $('body').append(popupDOM);

  // Close function
  $("#custom-popup .close").on("click", function () {
    closeModal();
  });
  $(document).mouseup(function (e) {
    let container = $(".popup-wrapper");
    if (!container.is(e.target) && container.has(e.target).length === 0) {
      closeModal();
    }
  });


  function openModal(msg) {
    let modal = $("#custom-popup");
    modal.find('.msg').text(msg)
    $(document).trigger("openCustomModal");
    modal.addClass("active");
  }

  function closeModal(msg) {
    let modal = $("#custom-popup");
    // setTimeout(() => {
        $(document).trigger("closeCustomModal");
      // }, 100);

      modal.removeClass("active");
  }
};

document.addEventListener("DOMContentLoaded", () => {
  init();
}) // End DOMContentLoaded