"use strict";

$(document).on('click','.paypal-button',function(){
  if(paypal_client_id != ""){
    $('#paypal-button').empty();

    var card = $(this).closest('.card');
    let card_progress = $.cardProgress(card, {
      spinner: true
    });
  
    var amount = $(this).data("amount");
    var plan_id = $(this).data("id");
    
    if(amount == 0){
      $.ajax({
        type: "POST",
        url: base_url+'plans/order-completed/', 
        data: "amount="+amount+"&plan_id="+plan_id,
        dataType: "json",
        success: function(result) 
        {	
          if(result['error'] == false){
              location.reload();
          }else{
            iziToast.error({
              title: result['message'],
              message: "",
              position: 'topRight'
            });
          }
        }        
      });
      return false;
    }

    paypal.Buttons({

      onClick: function(data, actions) {
        return fetch(base_url+'plans/validate/'+plan_id, {
          method: 'post',
          headers: {
            'content-type': 'application/json'
          }
        }).then(function(res) {
          return res.json();
        }).then(function(data) {
          if (data.validationError) {
            iziToast.error({
              title: "Something wrong! Try again.",
              message: "",
              position: 'topRight'
            });
            return actions.reject();
          } else {
            if(plan_id == data.plan[0]['id'] && amount == data.plan[0]['price']){
              return actions.resolve();
            }else{
            iziToast.error({
              title: "Something wrong! Try again.",
              message: "",
              position: 'topRight'
            });
              return actions.reject();
            }
          }
        });
      },

        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: amount
                    }
                }]
            });
        },

        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
              var status = 0;
              if(details.status == "COMPLETED"){
                status = 1; 
              }
              $.ajax({
                  type: "POST",
                  url: base_url+'plans/order-completed/', 
                  data: "amount="+amount+"&status="+status+"&plan_id="+plan_id,
                  dataType: "json",
                  success: function(result) 
                  {	
                    if(result['error'] == false){
                        location.reload();
                    }else{
                      iziToast.error({
                        title: result['message'],
                        message: "",
                        position: 'topRight'
                      });
                    }
                  }        
                });
            });
        }


    }).render('#paypal-button').then(function() { 
      $('html, body').animate({
        scrollTop: $("#paypal-button").offset().top
      }, 1000);
      card_progress.dismiss(function() {
			});
    });
  }else{
    iziToast.error({
      title: "Some Error occured. Please Try again later.",
      message: "",
      position: 'topRight'
    });
  }
});