/*!
 * Kratos
 * Seaton Jiang <seaton@vtrois.com> and Virace <Virace@aliyun.com>
 */
;(function () {
    'use strict'
    const KRATOS_VERSION = '1.0.0-rc';

    const navbarConfig = function () {
        function scrolled_navbar() {
            const scroll = window.scrollY;
            const $navbar = $(".k-nav.navbar")
            const $dropdown = $(".dropdown-menu")
            if (scroll > 0) {
                $navbar.addClass("navbar-scrolled");
                $dropdown.addClass("navbar-scrolled");
            } else {
                $navbar.removeClass("navbar-scrolled");
                $dropdown.removeClass("navbar-scrolled");
            }
        }


        // Scrolled navbar init
        $(window).scroll(function () {
            scrolled_navbar();
        });
        $(document).ready(function () {
            scrolled_navbar();
        });

        $(".auto-hiding-navbar").autoHidingNavbar({
            "animationDuration": 500,
            "showOnBottom": false,
            "showOnUpscroll": true
        });

        const $dropdown = $(".dropdown");
        const $dropdownToggle = $(".dropdown-toggle");
        const $dropdownMenu = $(".dropdown-menu");
        const showClass = "show";
        $(window).on("load resize", function () {
            if (this.matchMedia("(min-width: 992px)").matches) {
                $dropdown.hover(
                    function () {
                        const $this = $(this);
                        $this.addClass(showClass);
                        $this.find($dropdownToggle).attr("aria-expanded", "true");
                        $this.find($dropdownMenu).addClass(showClass);
                    },
                    function () {
                        const $this = $(this);
                        $this.removeClass(showClass);
                        $this.find($dropdownToggle).attr("aria-expanded", "false");
                        $this.find($dropdownMenu).removeClass(showClass);
                    }
                );

                let height = $('.navbar')[0].offsetHeight + 75
                $('body').scrollspy({
                    target: "#toc",
                    offset: height
                });
            } else {
                $dropdown.off("mouseenter mouseleave");
            }
        });

        // 移动端侧边栏
        const $clone = $('#navbarResponsive').clone();
        $clone.removeAttr("id");
        $clone.attr({'class': 'mobi-navbar'})
        $clone.find('*').removeAttr("id");
        $clone.find('*:not(.dropdown)').removeAttr("class");
        $clone.find('*').removeAttr("role");
        $clone.find('*').removeAttr("aria-current");
        $clone.find('*').removeAttr("aria-haspopup");
        $clone.find('*').removeAttr("aria-expanded");
        $clone.find('*').removeAttr("data-toggle");
        $clone.find('*').removeAttr("aria-labelledby");
        $clone.find('> ul').attr({'class': 'mobi-menu'})
        $clone.find('li > ul').attr({'class': 'mobi-menu-sub'})
        $clone.find('.dropdown').attr({'class': 'dropdown'})

        $('header.k-header').prepend($clone);

        function mobile_dropdown_menu() {
            const $dropdown = $(".mobi-navbar li.dropdown");
            for (let i = 0; i < $dropdown.length; i++) {
                const $item = $($dropdown[i]);
                if ($item.find(".m-dropdown").length === 0) {
                    $item.append(
                        '<div class="m-dropdown"><span class="vicon i-down"></span></div>'
                    );
                }
            }
        }

        mobile_dropdown_menu()

        $('#navbutton').on('click', function (e) {
            $("body").toggleClass("menu-on");
            if ($(".navbar-on-shadow").length === 0) {
                $("#content").append('<div class="navbar-on-shadow"></div>');
            }
        })
        $(".m-dropdown")
            .on("click", function (e) {
                const $this = $(this).parent();
                $this.find("> .mobi-menu-sub").slideToggle("fast");
                $this.toggleClass("menu-open");
            })
        $('body').on("click", ".navbar-on-shadow", function () {
            $("#navbutton").trigger("click");
        })
        $('.mobi-menu').on('click', 'a', function () {
            $("#navbutton").trigger("click");
        })
    };

    const tooltipConfig = function () {
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    };

    const gotopConfig = function () {
        $(window).on('load', function () {
            const $win = $(window);
            if ($win.scrollTop() > 200) {
                $('.gotop').addClass('active')
            } else {
                $('.gotop').removeClass('active')
            }
            $win.scroll(function () {
                if ($win.scrollTop() > 200) {
                    $('.gotop').addClass('active')
                } else {
                    $('.gotop').removeClass('active')
                }
            })
        })
        $('.gotop').on('click', function (event) {
            event.preventDefault()
            $('html, body').animate({
                scrollTop: $('html').offset().top
            }, 500)
            return false
        })
    };

    const searchConfig = function () {
        $('.search').on('click', function (e) {
            $('.search-form').animate({
                width: '200px'
            }, 200), $('.search-form input').css('display', 'block')
            $(document).one('click', function () {
                $('.search-form').animate({
                    width: '0'
                }, 100), $('.search-form input').hide()
            })
            e.stopPropagation()
        })
        $('.search-form').on('click', function (e) {
            e.stopPropagation()
        })
    };

    const wechatConfig = function () {
        let elem = $('.wechat')
        elem.mouseout(function () {
            $('.wechat-pic')[0].style.display = 'none'
        })
        elem.mouseover(function () {
            $('.wechat-pic')[0].style.display = 'block'
        })
    };

    const smiliesConfig = function () {
        $('#addsmile').on("click", function (e) {
            $('.smile').toggleClass('open')
            $(document).one("click", function () {
                $('.smile').toggleClass('open')
            })
            e.stopPropagation()
            return false
        })
    };

    const postlikeConfig = function () {
        $.fn.postLike = function () {
            if ($(this).hasClass('done')) {
                layer.msg(kratos.repeat, function () {
                })
                return false
            } else {
                $(this).addClass('done')
                layer.msg(kratos.thanks)
                var id = $(this).data("id"),
                    action = $(this).data('action')
                var ajax_data = {
                    action: "love",
                    um_id: id,
                    um_action: action
                }
                $.post(kratos.site + '/wp-admin/admin-ajax.php', ajax_data, function (data) {
                })
                return false
            }
        }
        $(document).on("click", ".btn-thumbs", function () {
            $(this).postLike()
        })
    };

    const donateConfig = function () {
        $("#donate").on('click', function () {
            layer.open({
                type: 1,
                area: ['300px', '370px'],
                title: kratos.donate,
                resize: false,
                scrollbar: false,
                content: '<div class="donate-box"><div class="meta-pay text-center my-2"><strong>' + kratos.scan + '</strong></div><div class="qr-pay text-center"><img class="pay-img" id="alipay_qr" src="' + kratos.alipay + '"><img class="pay-img d-none" id="wechat_qr" src="' + kratos.wechat + '"></div><div class="choose-pay text-center mt-2"><input id="alipay" type="radio" name="pay-method" checked><label for="alipay" class="pay-button"><img src="' + kratos.directory + '/assets/img/payment/alipay.png"></label><input id="wechatpay" type="radio" name="pay-method"><label for="wechatpay" class="pay-button"><img src="' + kratos.directory + '/assets/img/payment/wechat.png"></label></div></div>'
            })
            $(".choose-pay input[type='radio']").click(function () {
                const id = $(this).attr("id");
                if (id === 'alipay') {
                    $(".qr-pay #alipay_qr").removeClass('d-none');
                    $(".qr-pay #wechat_qr").addClass('d-none')
                }
                if (id === 'wechatpay') {
                    $(".qr-pay #alipay_qr").addClass('d-none');
                    $(".qr-pay #wechat_qr").removeClass('d-none')
                }
            })
        })
    };

    const accordionConfig = function () {
        $(document).on('click', '.acheader', function (event) {
            const $this = $(this);
            $this.closest('.accordion').find('.contents').slideToggle(300)
            if ($this.closest('.accordion').hasClass('active')) {
                $this.closest('.accordion').removeClass('active')
            } else {
                $this.closest('.accordion').addClass('active')
            }
            event.preventDefault()
        })
    };

    const consoleConfig = function () {
        console.log('\n Kratos v' + KRATOS_VERSION + '\n\n https://github.com/vtrois/kratos \n\n')
    };
    const imgLayerConfig = function () {
        layer.photos({
            photos: '.article .content .wp-block-image',
            anim: 0
        });
    }

    const highlightConfig = function (flag) {
        function action() {
            document.querySelectorAll('pre code').forEach((block) => {
                hljs.highlightBlock(block);
                hljs.lineNumbersBlock(block);
                hljs.addCopyButton(block);
            });
        }

        if ($('pre code').length >= 1) {
            if (typeof hljs == 'undefined') {
                $('head').append('<link href="' + kratos.directory + '/assets/css/highlight/style.min.css" rel="stylesheet" type="text/css" />')

                let JSDependencies = [kratos.directory + '/assets/js/highlight/highlight.pack.js',
                    kratos.directory + '/assets/js/highlight/highlightjs-copy-button.min.js',
                    kratos.directory + '/assets/js/highlight/highlightjs-line-numbers.min.js'];
                loadScripts(JSDependencies, '').then(action);
            } else {
                action()
            }
        }
    }

    window['pjax_reload'] = function () {
        tooltipConfig()
        wechatConfig()
        smiliesConfig()
        postlikeConfig()
        donateConfig()
        highlightConfig()
        imgLayerConfig()
    }
    $(function () {
        accordionConfig()
        navbarConfig()
        tooltipConfig()
        gotopConfig()
        searchConfig()
        wechatConfig()
        smiliesConfig()
        postlikeConfig()
        donateConfig()
        consoleConfig()
        imgLayerConfig()
        highlightConfig()
    })
}())

function loadScripts(urls, path) {
    return new Promise(function (resolve) {
        urls.forEach(function (src, i) {

            let script = document.createElement('script');
            script.type = 'text/javascript';
            script.src = (path || "") + src;
            script.async = false;

            // If last script, bind the callback event to resolve
            if (i === urls.length - 1) {
                // Multiple binding for browser compatibility
                script.onreadystatechange = resolve;
                script.onload = resolve;
            }

            // Fire the loading
            document.body.appendChild(script);
        });
    });
}

function grin(tag) {
    let myField, sel;
    tag = ' ' + tag + ' '
    if (document.getElementById('comment') && document.getElementById('comment').type === 'textarea') {
        myField = document.getElementById('comment')
    } else {
        return false
    }
    if (document.selection) {
        myField.focus()
        sel = document.selection.createRange()
        sel.text = tag
        myField.focus()
    } else if (myField.selectionStart || myField.selectionStart === '0') {
        const startPos = myField.selectionStart;
        const endPos = myField.selectionEnd;
        let cursorPos = endPos;
        myField.value = myField.value.substring(0, startPos)
            + tag
            + myField.value.substring(endPos, myField.value.length)
        cursorPos += tag.length
        myField.focus()
        myField.selectionStart = cursorPos
        myField.selectionEnd = cursorPos
    } else {
        myField.value += tag
        myField.focus()
    }
}

document.addEventListener("DOMContentLoaded", function (event) {
    const cloneDOMContentLoaded = new Event("cloneDOMContentLoaded");
    document.dispatchEvent(cloneDOMContentLoaded);
});

AOS.init({
    disable: 'mobile',
    startEvent: 'cloneDOMContentLoaded',
    initClassName: 'aos-init',
    animatedClassName: 'aos-animate',
    useClassNames: false,
    disableMutationObserver: false,
    debounceDelay: 50,
    throttleDelay: 99,

    offset: 40,
    delay: 0,
    duration: 1000,
    easing: 'ease-in-out',
    once: false,
    mirror: false,
    anchorPlacement: 'top-bottom',
});

$("body").on("click", 'a[href^="#toc"],area[href^="#toc"]', function () {
    if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
        let $target = $(this.hash);
        let height = $('.navbar')[0].offsetHeight
        $target = $target.length && $target || $('[name=' + this.hash.slice(1) + ']');
        if ($target.length) {
            const targetOffset = $target.offset().top;
            $('html,body').animate({
                    scrollTop: targetOffset - height - 5
                },
                1000);
            return false;
        }
    }
});