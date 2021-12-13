// JavaScript Document
$(function () {

	////////login

	$("[placeholder]").on("focus", function () {
		$(this).css({ boxShadow: "0px 0px 5px 0px #1f49f3" })// to add box shadow on focus
		a = $(this).attr('placeholder')
		$(this).attr('placeholder', '')//to remove place holder on fucus
	});
	$("[placeholder]").on("blur", function () {
		$(this).css({ boxShadow: "none" })
		$(this).attr('placeholder', a)//back to original placeholder
		a = '';
	});

	//////end login
	///start naviation bar


	$(".center").on('click', function () {

		$(this).children().slideToggle(200);
	});
	$(".slide_nav").on('click', function () {
		$(".slide-down a").slideToggle(500);
		$(".line2").toggle();
		$(".slide_nav :first-child").toggleClass('line1');
		$(".slide_nav :last-child").toggleClass("line3");
		$('.search_items').children('form').fadeToggle();

	})
	$(".left>ul>a").on("click", function () {
		$(this).children("ul").alideToggle();

	})


	/////end navigation bar

	// start edit page
	$(".edit_profile>div>input").one("focus", function () {
		$(this).select()

	})
	//password eye
	passeye = '.password_eye';
	passdeye = '.password_deye'
	$(passeye).on("click", function () {
		$("[type='password']").attr('type', 'text');
		$(passeye).hide();
		$(passdeye).show();
	});
	$(passdeye).on("click", function () {
		$(this).prev().prev().attr('type', 'password');
		$(passeye).show();
		$(passdeye).hide();



	});


	// end edit page
	// start redirected page

	// end  redirected page

	$("[required='required']").before("<span>*</span>")
	//confirm deleted
	$(".confirm").on("click", function () {
		return confirm('Are You Sure?');

	});

	/*=============start category page==================*/
	//$(".catigories_list>div").slideDown(200);

	$(".sort_list").click(function () {
		$(this).siblings().children("div").hide();
		$(this).siblings().children("i").show();
		$(this).children().fadeToggle(100);

	});
	$(".c_sort").click(function () {
		$(this).next().slideToggle(100);



	})

	/*category list option*/

	$(".catigories_list>div").click(function () {
		$(this).children("div").slideToggle(200);
		$(this).siblings().children("div").fadeOut(200);
	})
	/*--------------end category page--------------*/
	/*--------------start add item page--------------*/
	/** live  item */
	$(".live_name").keyup(function () { $(".items h3").text($(this).val()) })
	$(".live_description").keyup(function () { $(".items h5").text($(this).val()) })
	$(".live_price").keyup(function () { $(".items .price").text('$ ' + $(this).val()) })
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();


			reader.onload = function () {
				$('#imgInp').attr('src', this.result);
			}
			reader.readAsDataURL(input.files[0]); // convert to base64 string
		} else {
			$('#imgInp').attr('src', 'online-marketing-1246457_640.jpg');
		}
	}

	$("#item_image").change(function () {
		if ($(window).width() > 730) {
			readURL(this);
		}
	});

	//notification

	$('.notification').on('click', function () {
		if ($(this).css('color') == 'rgb(204, 204, 204)') { $(this).css('color', '#91e58f') }
		else { $(this).css('color', '#cccccc') }
		$(this).children('div').fadeToggle();
		$(this).children('div').next().fadeToggle();

	})

	// cart
	$('.product_amount').on('dblclick', function () {
		$(this).children('.amount').removeAttr('disabled');
	});
	$('.product_amount').children('.amount').on('blur', function () {
		var item_id = $(this).next().val();
		var amount = $(this).val();
		var ip = $(this).next().next().val();
		var a = $(this);

		console.info(' this is id : ' + item_id)
		console.log(' this is amount : ' + amount)
		console.log(' this is id : ' + ip)
		$.ajax({
			method: 'post',
			url: 'cart.php',
			data: { amount: amount, ip: ip, item_id: item_id },
			success: function (response) {
				a.attr('disabled', 'disabled');
			},
			beforeSend: function () { }




		});
	});





});
/** comment  */
$(".comment textarea").one("focus", function () {
	$(this).select()

});

//password eye
var passeye = '.password_eye';
var passdeye = '.password_deye'
$(passeye).on("click", function () {
	$("[type='password']").attr('type', 'text');
	$(passeye).hide();
	$(passdeye).show();
});
$(passdeye).on("click", function () {
	$(this).prev().prev().attr('type', 'password');
	$(passeye).show();
	$(passdeye).hide();



});









