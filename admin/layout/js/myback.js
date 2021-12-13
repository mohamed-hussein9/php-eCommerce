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

	})
	$(".left>ul>a").on("click", function () {
		$(this).children("ul").slideToggle();

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

	$("[required='required']").prev().before("<span>*</span>")
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

	// delete  item and user
	$(".delete_item_and_user").on('click', function (e) {
		var page = $(this).attr('href');
		if (page == 'items.php') {
			var $confirm_message = 'are you sure to delete this item... this will delete all items related';
		}
		else { var $confirm_message = 'are you sure to delete this member... this will delete all mumbers and items related' }
		e.preventDefault();
		if (confirm($confirm_message)) {
			var id_d = $(this).attr('delete_id');
			var com = $(this).parent().parent();
			var this_but = $(this);
			$.ajax({
				method: "GET",
				url: page,
				data: { do: 'delete', id: id_d },
				success: function () {
					this_but.html('DELETED');
					iziToast.success({
						title: 'done',
						message: 'Successfully deleted'
					});
					com.slideUp();
				},
				beforeSend: function () {
					this_but.css('pointer-events', 'none');
					this_but.html('Wait...');
				}
			})
		}
	});// delete  item and user

	// Approve items and members and comments
	$(".approve").on('click', function (e) {
		e.preventDefault();
		if (confirm("are you sure to approve")) {
			var approve_id = $(this).attr('approve_id');
			var page = $(this).attr('href');
			var com = $(this);
			$.ajax({
				method: "GET",
				url: page,
				data: { do: 'approve', id: approve_id },
				success: function () {
					com.css('display', 'none')
					iziToast.success({
						title: 'done',
						message: 'Successfully Approved'
					});
				},
				beforeSend: function () {
					com.html('Wait...');
				}
			})
		}
	});// end approval requests

	// notification
	$('.notification').on('click', function () {
		if ($(this).css('color') == 'rgb(204, 204, 204)') { $(this).css('color', '#91e58f') }
		else { $(this).css('color', '#cccccc') }
		$(this).siblings().css('color', '#cccccc')
		$(this).children('div').fadeToggle();
		$(this).siblings().children('div').fadeOut(200);
	})


});







