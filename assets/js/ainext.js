(function($) {
	'use strict';

	// Sticky Navbar
    $(window).on('scroll', function() {
        var $navbar = $('.navbar'),
            $mbNav = $('.mb-nav');

        $navbar.toggleClass("sticky", $(this).scrollTop() > 100);
        $mbNav.toggleClass("sticky", $(this).scrollTop() > 50);
    });

	// Responsive Menu
    $(document).on('click', '.responsive-menu-list', function(e) {
        e.stopPropagation();
        var $this = $(this);
        $('.responsive-menu-list').removeClass('active');
        $this.toggleClass('active', !$this.hasClass('active'));
    });

	// Hover Button
    $(document).on('mouseenter mouseout', '.main-btn', function(e) {
        var parentOffset = $(this).offset(),
            relX = e.pageX - parentOffset.left,
            relY = e.pageY - parentOffset.top;
        $(this).find('span').css({top: relY, left: relX});
    });

	// Odometer JS
	$('.odometer').appear(function() {
		$(".odometer").each(function() {
			$(this).html($(this).attr("data-count"));
		});
	});

	// Team Slides
	$('.image-courser').owlCarousel({
		nav: true,
		loop: true,
		dots: false,
		margin: 30,
		autoplay: true,
		autoplayTimeout: 2000,
		autoplayHoverPause: true,
		navText: [
			"<i class='fi fi-tr-angle-small-left'></i>",
			"<i class='fi fi-tr-angle-small-right'></i>"
		],
		responsive: {
			0: {
				items: 1
			},
			576: {
				items: 2
			},
			768: {
				items: 2
			},
			992: {
				items: 3
			},
			1200: {
				items: 3
			}
		}
	});

	// Gallery Filtering
    $(document).on('click', '.item-list', function() {
        var value = $(this).attr('data-filter');
        if (value === 'all') {
            $('.item-box').show('1000');
        } else {
            $('.item-box').hide('1000').filter('.' + value).show('1000');
        }
    });
	
    // Play Button
    $(document).on('click', '.play', function() {
        $(this).addClass('on').siblings().removeClass('on');
    });

	// Testimonial Slides
	$('.testimonial-content').owlCarousel({
		nav: true,
		loop: true,
		dots: false,
		margin: 30,
		autoplay: true,
		autoplayTimeout: 2000,
		autoplayHoverPause: true,
		navText: [
			"<i class='fi fi-tr-arrow-left'></i>",
			"<i class='fi fi-tr-arrow-right'></i>"
		],
		responsive: {
			0: {
				items: 1
			},
			576: {
				items: 1
			},
			768: {
				items: 1
			},
			992: {
				items: 1
			},
			1200: {
				items: 1
			}
		}
	});

	// Article Slider
	$('.article-content').owlCarousel({
		dots: true,
		nav: false,
		loop: true,
		margin: 30,
		autoplay: true,
		autoplayTimeout: 2000,
		autoplayHoverPause: false,
		responsive: {
			0: {
				items: 1
			},
			576: {
				items: 1
			},
			768: {
				items: 2
			},
			992: {
				items: 1
			},
			1200: {
				items: 2
			}
		}
	});
	
	// Instagram slider
	$('.ins-gallery').owlCarousel({
		nav: false,
		dots: false,
		loop: true,
		autoplay: true,
		autoplayTimeout: 1900,
		autoplayHoverPause: true,
		responsive: {
			0: {
				items: 3
			},
			576: {
				items: 2
			},
			768: {
				items: 5
			},
			992: {
				items: 6
			},
			1200: {
				items: 10
			}
		}
	});
	
	// AOS Animation
	AOS.init();

	// Back to Top Button
	let calcScrollValue = () => {
		let scrollProgress = document.getElementById("progress");
		let progressValue = document.getElementById("progress-value");
		let pos = document.documentElement.scrollTop;
		let calcHeight =
		  document.documentElement.scrollHeight -
		  document.documentElement.clientHeight;
		let scrollValue = Math.round((pos * 100) / calcHeight);
		if (pos > 100) {
		  	scrollProgress.style.display = "grid";
		} else {
		  	scrollProgress.style.display = "none";
		}
		scrollProgress.addEventListener("click", () => {
		  	document.documentElement.scrollTop = 0;
		});
		scrollProgress.style.background = `conic-gradient(#7f00ff ${scrollValue}%, #9094a6 ${scrollValue}%)`;
	  };
	  
	  window.onscroll = calcScrollValue;
	  window.onload = calcScrollValue;

	// Contact Form Handler
	$('#contactForm').on('submit', function(e) {
		e.preventDefault();
		
		var $form = $(this);
		var $submitBtn = $form.find('button[type="submit"]');
		var originalText = $submitBtn.html();
		
		// Disable submit button and show loading
		$submitBtn.prop('disabled', true).html('<i class="ri-loader-4-line"></i> Sending...');
		
		// Clear previous error messages
		$form.find('.help-block').text('');
		$form.find('.form-group').removeClass('has-error');
		
		// Get form data
		var formData = {
			name: $form.find('#name').val(),
			email: $form.find('#email').val(),
			subject: $form.find('#subject').val(),
			phone_number: $form.find('#phone_number').val(),
			message: $form.find('#message').val()
		};
		
		// Basic client-side validation
		var isValid = true;
		
		if (!formData.name.trim()) {
			$form.find('#name').closest('.form-group').addClass('has-error');
			$form.find('#name').next('.help-block').text('Please enter your name');
			isValid = false;
		}
		
		if (!formData.email.trim()) {
			$form.find('#email').closest('.form-group').addClass('has-error');
			$form.find('#email').next('.help-block').text('Please enter your email address');
			isValid = false;
		} else if (!isValidEmail(formData.email)) {
			$form.find('#email').closest('.form-group').addClass('has-error');
			$form.find('#email').next('.help-block').text('Please enter a valid email address');
			isValid = false;
		}
		
		if (!formData.subject.trim()) {
			$form.find('#subject').closest('.form-group').addClass('has-error');
			$form.find('#subject').next('.help-block').text('Please enter your subject');
			isValid = false;
		}
		
		if (!formData.phone_number.trim()) {
			$form.find('#phone_number').closest('.form-group').addClass('has-error');
			$form.find('#phone_number').next('.help-block').text('Please enter your phone number');
			isValid = false;
		}
		
		if (!formData.message.trim()) {
			$form.find('#message').closest('.form-group').addClass('has-error');
			$form.find('#message').next('.help-block').text('Please enter your message');
			isValid = false;
		}
		
		if (!isValid) {
			$submitBtn.prop('disabled', false).html(originalText);
			return;
		}
		
		// Send AJAX request
		$.ajax({
			url: 'contact-handler.php',
			type: 'POST',
			data: formData,
			dataType: 'json',
			success: function(response) {
				if (response.success) {
					// Show success message
					showNotification('success', response.message);
					$form[0].reset();
				} else {
					// Show error message
					showNotification('error', response.message);
					
					// Show field-specific errors if available
					if (response.errors) {
						response.errors.forEach(function(error) {
							// Try to match error to specific field
							if (error.toLowerCase().includes('name')) {
								$form.find('#name').closest('.form-group').addClass('has-error');
								$form.find('#name').next('.help-block').text(error);
							} else if (error.toLowerCase().includes('email')) {
								$form.find('#email').closest('.form-group').addClass('has-error');
								$form.find('#email').next('.help-block').text(error);
							} else if (error.toLowerCase().includes('subject')) {
								$form.find('#subject').closest('.form-group').addClass('has-error');
								$form.find('#subject').next('.help-block').text(error);
							} else if (error.toLowerCase().includes('phone')) {
								$form.find('#phone_number').closest('.form-group').addClass('has-error');
								$form.find('#phone_number').next('.help-block').text(error);
							} else if (error.toLowerCase().includes('message')) {
								$form.find('#message').closest('.form-group').addClass('has-error');
								$form.find('#message').next('.help-block').text(error);
							}
						});
					}
				}
			},
			error: function(xhr, status, error) {
				showNotification('error', 'Sorry, there was an error sending your message. Please try again later.');
			},
			complete: function() {
				// Re-enable submit button
				$submitBtn.prop('disabled', false).html(originalText);
			}
		});
	});
	
	// Email validation function
	function isValidEmail(email) {
		var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
		return emailRegex.test(email);
	}
	
	// Notification function
	function showNotification(type, message) {
		// Remove existing notifications
		$('.notification').remove();
		
		var notificationClass = type === 'success' ? 'alert-success' : 'alert-danger';
		var icon = type === 'success' ? 'ri-check-line' : 'ri-error-warning-line';
		
		var notification = $('<div class="notification alert ' + notificationClass + ' alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">' +
			'<i class="' + icon + '"></i> ' + message +
			'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
			'</div>');
		
		$('body').append(notification);
		
		// Auto-hide after 5 seconds
		setTimeout(function() {
			notification.alert('close');
		}, 5000);
	}
	
	// Clear error messages on input focus
	$('#contactForm input, #contactForm textarea').on('focus', function() {
		$(this).closest('.form-group').removeClass('has-error');
		$(this).next('.help-block').text('');
	});
		
})(jQuery);