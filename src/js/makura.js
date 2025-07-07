$(function () {

    function bodyOverflowHide() {
        $("body").addClass("overflow-hidden");
    }
    function bodyOverflowShow() {
        $("body").removeClass("overflow-hidden");
    }
    /** MAKURA MODAL */
    $("[data-modal]").on("click", function (e) {
        e.preventDefault();
        bodyOverflowHide();
        const currentModal = $(this).data("modal");
        const currentModalElement = $(`#${currentModal}`);
        const videoUrl = $(this).data("video-url");

        // Convert YouTube short URL to embed format
        let embedUrl = "";
        if (videoUrl) {
            if (videoUrl.includes("youtu.be")) {
                const videoId = videoUrl.split("youtu.be/")[1].split("?")[0];
                embedUrl = `https://www.youtube.com/embed/${videoId}`;
            } else if (videoUrl.includes("youtube.com/watch")) {
                const videoId = new URL(videoUrl).searchParams.get("v");
                embedUrl = `https://www.youtube.com/embed/${videoId}`;
            }
        }

        currentModalElement.addClass("active");
        currentModalElement.find(".makura-modal-body iframe").attr("src", embedUrl);
    });


    $(".makura-modal").on("click", function (e) {
        if (!$(e.target).closest(".makura-modal-content").length) {
            $(this).removeClass("active");
            bodyOverflowShow();
        }
    })
    $("[close-modal]").on("click", function (e) {
        e.preventDefault();
        $(".makura-modal").removeClass("active");
        bodyOverflowShow();
    })

    /** MAKURA INCREMENT  */
    $(".cart-item-changer .inc, .cart-item-changer .dec").on("click", function (e) {
        e.preventDefault();

        const $wrapper = $(this).closest(".cart-item-changer");
        const $input = $wrapper.find("input[type='number']");
        let currentValue = parseInt($input.val(), 10) || 0;

        if ($(this).hasClass("inc")) {
            $input.val(currentValue + 1);
        } else if ($(this).hasClass("dec")) {
            if (currentValue > 0) {
                $input.val(currentValue - 1);
            }
        }
    });

    /** CONTACT FORM 7 FIXES */
    $(function () {
        const toastController = ({ message, variant }) => {
            var toast = $('.makura-toast')

            toast.find('p').text(message)
            toast.removeClass('success info warning error').addClass(variant)

            setTimeout(() => {
                toast.removeClass(variant)
            }, 5000)
        }

        // Handle all CF7 events
        document.addEventListener('wpcf7invalid', function (event) {
            setTimeout(function () {
                const message = event.target.querySelector(
                    '.wpcf7-response-output'
                )?.innerText
                toastController({ message, variant: 'error' })

                // Focus first invalid field
                const firstInvalid = event.target.querySelector('.wpcf7-not-valid')
                if (firstInvalid) {
                    firstInvalid.focus()
                }
            }, 0)
        })

        document.addEventListener('wpcf7spam', function (event) {
            setTimeout(function () {
                const message = event.target.querySelector(
                    '.wpcf7-response-output'
                )?.innerText
                toastController({ message, variant: 'warning' })
            }, 0)
        })

        document.addEventListener('wpcf7mailsent', function (event) {
            setTimeout(function () {
                const message = event.target.querySelector(
                    '.wpcf7-response-output'
                )?.innerText
                toastController({ message, variant: 'success' })
            }, 0)
            $('.popup').removeClass('active')
        })

        document.addEventListener('wpcf7mailfailed', function (event) {
            setTimeout(function () {
                const message = event.target.querySelector(
                    '.wpcf7-response-output'
                )?.innerText
                toastController({ message, variant: 'error' })
                send
            }, 0)
        })

        // Close toast button
        $('.close-toast').on('click', function (e) {
            e.preventDefault()
            $('.makura-toast').removeClass('success info warning error')
        })
    })
})
