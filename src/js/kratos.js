/*!
 * Kratos
 * Seaton Jiang <seaton@vtrois.com> and Virace <Virace@aliyun.com>
 */
(function () {
  'use strict'
  const KRATOS_VERSION = '1.0.1'

  const navbarConfig = function () {
    const $menu = $('.dropdown-menu')

    function s () {
      const h = window.scrollY,
        $navbar = $('.k-nav.navbar')
      h > 0 ? ($navbar.addClass('navbar-scrolled'), $menu.addClass('navbar-scrolled')) : ($navbar.removeClass('navbar-scrolled'), $menu.removeClass('navbar-scrolled'))
    }

    $(window).scroll(s)
    $(document).ready(s)

    $('.auto-hiding-navbar').autoHidingNavbar({
      animationDuration: 500,
      showOnBottom: !1,
      showOnUpscroll: !0
    })
    const dropdown = $('.dropdown'),
      $toggle = $('.dropdown-toggle')

    $(window).on('load resize', function () {

      if (this.matchMedia('(min-width: 992px)').matches) {

        dropdown.hover(function () {
          const $this = $(this)
          $this.find($menu).stop().fadeIn('fast')
          $this.find($toggle).attr('aria-expanded', 'true')
        }, function () {
          const $this = $(this)
          $this.find($menu).stop().fadeOut('fast')
          $this.find($toggle).attr('aria-expanded', 'false')
        })
        let height = $('.navbar')[0].offsetHeight + 75
        $('body').scrollspy({
          target: '#toc',
          offset: height
        })
      } else dropdown.off('mouseenter mouseleave')
    })

    const mobiBar = $('#navbarResponsive').clone()
    mobiBar.removeAttr('id')
    mobiBar.attr({ class: 'mobi-navbar' })
    mobiBar.find('*').removeAttr('id')
    mobiBar.find('*:not(.dropdown)').removeAttr('class')
    mobiBar.find('*').removeAttr('role')
    mobiBar.find('*').removeAttr('aria-current')
    mobiBar.find('*').removeAttr('aria-haspopup')
    mobiBar.find('*').removeAttr('aria-expanded')
    mobiBar.find('*').removeAttr('data-toggle')
    mobiBar.find('*').removeAttr('aria-labelledby')
    mobiBar.find('> ul').attr({ class: 'mobi-menu' })
    mobiBar.find('li > ul').attr({ class: 'mobi-menu-sub' })
    mobiBar.find('.dropdown').attr({ class: 'dropdown' })

    $('header.k-header').prepend(mobiBar)

    // 为二级菜单添加下拉图标
    const mobiBarItem = $('.mobi-navbar li.dropdown')
    for (let l = 0; l < mobiBarItem.length; l++) {
      const ele = $(mobiBarItem[l])
      ele.find('.m-dropdown').length === 0 && ele.append('<div class="m-dropdown"><span class="vicon i-down"></span></div>')
    }

    $('#navbutton').on('click', function (h) {
      $('body').toggleClass('menu-on')
      $('.navbar-on-shadow').length === 0 && $('#content').append('<div class="navbar-on-shadow"></div>')
    })

    $('.m-dropdown').on('click', function (h) {
      const parent = $(this).parent()
      parent.find('> .mobi-menu-sub').slideToggle('fast')
      parent.toggleClass('menu-open')
    })

    $('body').on('click', '.navbar-on-shadow', function () {
      $('#navbutton').trigger('click')
    })

    $('.mobi-menu').on('click', 'a', function () {
      $('#navbutton').trigger('click')
    })
  }

  const gotopConfig = function () {
    const $top = $('.gotop'),
      height = $('.navbar')[0].offsetHeight + 75,
      $win = $(window)
    // $(window).on('load', function () {
    //   const $win = $(window)
    //   if ($win.scrollTop() > height) {
    //     $top.fadeIn(300)
    //   } else {
    //     $top.fadeOut(300)
    //   }
    //   $win.scroll(function () {
    //     if ($win.scrollTop() > height) {
    //       $top.fadeIn(300)
    //     } else {
    //       $top.fadeOut(300)
    //     }
    //   })
    // })
    $win.scroll(function () {
      if ($win.scrollTop() > height) {
        $top.fadeIn(300)
      } else {
        $top.fadeOut(300)
      }
    })
    $top.on('click', function (t) {
      t.preventDefault()
      $('html, body').animate({
        scrollTop: $('html').offset().top
      }, 500)
      return !1
    })
  }

  const wechatConfig = function () {
    const $wechatBtn = $('.wechat .i-wechat'),
      $wechatPic = $('.wechat-pic')
    $wechatBtn.hover(function () {
      $wechatPic.stop().fadeIn('fast')
    }, function () {
      $wechatPic.stop().fadeOut('fast')
    })
  }
  const tabConfig = function () {
    $(document.body).on('click', '.nav-tabs > a.nav-item.nav-link', function (e) {

      $('.nav-tabs > a.nav-item.nav-link').removeClass('active')

      $(this).addClass('active')

      const content = $($(this).attr('href'))
      let transitionTime = getTransitionTime(content)
      $('.tab-content .tab-pane').removeClass('show')
      setTimeout(() => {
        content.siblings().removeClass('active')
        content.addClass('active')
      }, transitionTime)
      // 两个计时器异步进行
      setTimeout(() => {
        content.addClass('show')
      }, transitionTime * 2 - 50)

      e.preventDefault()
    })
  }

  const modalConfig = function () {
    $(document.body).on('click', '.btn[data-toggle="modal"]', function (e) {
      const target = $(this).attr('data-target')
      let $target = $(target)
      const $content = $('#content .container')
      if ($target) {
        $target.removeClass('hide').addClass('show')
        $content.removeClass('hide').addClass('show')
        $(document.body).addClass('modal-active')
        // 取消modal内部元素点击事件
        $target.find('.modal-content>*').on('click', function (e) {
          e.stopPropagation()
        })

        const _close = function () {
          $target.addClass('hide')
          $content.addClass('hide').removeClass('show')
          $(document.body).removeClass('modal-active')
        }
        $target.find('.btn-close').one('click', _close)
        $target.one('click', _close)
      } else return 0
    })
  }

  const smiliesConfig = function () {
    const $smile = $('.smile')
    function action (){
      if(!$smile.hasClass('show')){
        $('.smile img').each(function () {
          const $this = $(this)
          $this.attr('src', $this.attr('data-src'))
          $this.removeAttr('data-src');
        })
        $smile.addClass('show')
      }
    }
    $('#addsmile').on('click', function (e) {
      $smile.fadeToggle(300)
      // 延迟加载表情
      action()
      $(document).one('click', function () {
        $smile.fadeToggle(300)
      })
      e.stopPropagation()
      return !1
    })
  }

  const postlikeConfig = function () {
    $.fn.postLike = function () {
      if ($(this).hasClass('done')) {
        toast.msg(kratos.repeat)
        return !1
      }
      $(this).addClass('done')
      toast.msg(kratos.thanks)
      const id = $(this).data('id'),
        action = $(this).data('action'),
        data = {
          action: 'love',
          um_id: id,
          um_action: action
        }
      $.post(kratos.site + '/wp-admin/admin-ajax.php', data, function (e) {})
      return !1

    }
    $(document).on('click', '.btn-thumbs', function () {
      $(this).postLike()
    })
  }

  const donateConfig = function () {
    $('.choose-pay input[type=\'radio\']').on('click', function () {
      const id = $(this).attr('id')
      id === 'alipay' && ($('.qr-pay #alipay_qr').removeClass('d-none'), $('.qr-pay #wechat_qr').addClass('d-none'))
      id === 'wechatpay' && ($('.qr-pay #alipay_qr').addClass('d-none'), $('.qr-pay #wechat_qr').removeClass('d-none'))
    })
  }
  const acheaderConfig = function () {
    $(document).on('click', '.acheader', function (s) {
      const $this = $(this)
      // $this.closest('.accordion').toggleClass('active')
      $this.closest('.accordion').find('.contents').slideToggle(300)
      $this.closest('.accordion').toggleClass('active')
      // if ($this.closest('.accordion').hasClass('active')) {
      //   $this.closest('.accordion').removeClass('active')
      // } else {
      //   $this.closest('.accordion').addClass('active')
      // }
    })
  }
  const consoleConfig = function () {
    console.log('Kratos-pe v' + KRATOS_VERSION + 'https://github.com/Virace/kratos-pe')
  }
  const photoConfig = function () {
    const el = document.getElementById('content')

    function action () {
      lightGallery(el, {
        mode: 'lg-slide-circular',
        selector: 'figure.wp-block-image, div.wp-block-image > figure, .blocks-gallery-item figure'
      })
    }
    const flagElem = $('#content > article > div.content')
    const imgs = $('.article .content img')
    if (imgs.length >= 1 && !flagElem.hasClass('lightgallery')) {
      flagElem.addClass('lightgallery')
      imgs.each(function (c) {
        const parent = $(this.parentNode)
        parent.attr('data-src', this.src)
        const intro = parent.find('figcaption')
        intro.length && parent.attr('data-sub-html', '<h4>' + intro.text() + '</h4>')
      })

      if (typeof lightGallery == 'undefined') {
        const path = kratos.directory,
          css = [path + '/assets/css/lightgallery.js/lightgallery.min.css', path + '/assets/css/lightgallery.js/lg-transitions.min.css'],
          js = [path + '/assets/js/lightgallery.js/lightgallery.min.js', path + '/assets/js/lightgallery.js/lg-thumbnail.min.js']
        loadRes.LoadCSS(css, '').then(), loadRes.LoadJS(js).then(action)
      } else {
        try {
          window.lgData[el.getAttribute('lg-uid')].destroy(!0)
        } catch (c) {}
        action()
      }
    }
  }

  const photoLazy = function () {
    const images = $('.article .content img')
    const flagElem = $('#content > article > div.content')
    const thumb = kratos.loading
    if (images.length >= 1 && !flagElem.hasClass('photoLazy')) {
      flagElem.addClass('photoLazy')
      // 图片加载前先替换自身src
      images.each(function () {
        const $this = $(this)
        const src = this.src
        $this.attr('data-lazy', src)
        $this.attr('src', thumb)
      })
      // 获取动画时间, animate.css 默认时间为1秒, --animate-duration
      const delay = 1000
      setTimeout(function () {
        images.each(function () {
          const $this = $(this)
          $this.attr('src', $this.attr('data-lazy'))
          $this.removeAttr('data-lazy');
        })
      }, delay)
    }

  }

  const highlightConfig = function (s) {
    function action () {
      document.querySelectorAll('pre code').forEach(n => {
        hljs.highlightBlock(n), hljs.lineNumbersBlock(n), hljs.addCopyButton(n)
      })
    }

    if ($('pre code').length >= 1)
      if (typeof hljs == 'undefined') {
        const path = kratos.directory
        loadRes.LoadCSS([path + '/assets/css/highlight/style.min.css'], '').then(r => {})
        let c = [path + '/assets/js/highlight/highlight.pack.js', path + '/assets/js/highlight/highlightjs-copy-button.min.js', path + '/assets/js/highlight/highlightjs-line-numbers.min.js']
        loadRes.LoadJS(c, '').then(action)
      } else action()
  }
  window.pjax_reload = function () {
    wechatConfig()
    smiliesConfig()
    postlikeConfig()
    donateConfig()
    highlightConfig()
    photoConfig()
    modalConfig()
    tabConfig()
    photoLazy()
  }
  $(function () {
    acheaderConfig()
    navbarConfig()
    modalConfig()
    tabConfig()
    gotopConfig()
    wechatConfig()
    smiliesConfig()
    postlikeConfig()
    donateConfig()
    consoleConfig()
    photoConfig()
    highlightConfig()
    photoLazy()
  })
})()

const loadRes = {
  _load: function (file, path, a) {
    let ele = {}
    switch (file) {
      case 'css': {
        ele = {
          tag: 'link',
          attr: {
            type: 'text/css',
            rel: 'stylesheet'
          }
        }
        break
      }
      case 'js': {
        ele = {
          tag: 'script',
          attr: {
            type: 'text/javascript'
          }
        }
        break
      }
    }
    return new Promise(function (d) {
      path.forEach(function (m, p) {
        let f = document.createElement(ele.tag)
        Object.assign(f, ele.attr), file === 'css' ? f.href = (a || '') + m : f.src = (a || '') + m, f.async = !1, p === path.length - 1 && (f.onreadystatechange = d, f.onload = d), document.body.appendChild(f)
      })
    })
  }, LoadJS: function (o, i) {
    return this._load('js', o, i)
  }, LoadCSS: function (o, i) {
    return this._load('css', o, i)
  }
}
const toast = {
  _initEvent: function (msg) {
    // 创建元素并添加到body,
    const ele = document.createElement('div')
    ele.setAttribute('class', 'toast')
    ele.style.display = 'none'
    ele.innerHTML = '<div class="toast-body text-center">' + msg + '</div>'
    document.body.appendChild(ele)
    return $(ele)
  },
  msg: function (msg, delay = 2e3, callback = null) {
    const target = this._initEvent(msg, delay)
    // 居中显示, 因为只有一处使用toast, 所以未对onresize事件进行处理.
    target.css('left', document.documentElement.clientWidth / 2 - target.innerWidth() / 2)
    target.css('top', document.documentElement.clientHeight / 2 - target.innerHeight() / 2)
    target.fadeIn(300)
    setTimeout(function () {
      typeof callback == 'function' && callback()
      target.fadeOut(300)
      // 动画延迟结束删除元素
      setTimeout(function () {
        target.remove()
      }, 300)
    }, delay)

  }
}

function getTransitionTime (o) {
  if (!o) return 0
  let i = $(o).css('transition-duration'),
    a = $(o).css('transition-delay')
  const r = parseFloat(i),
    d = parseFloat(a)
  return !r && !d ? 0 : (i = i.split(',')[0], a = a.split(',')[0], (parseFloat(i) + parseFloat(a)) * 1e3)
}

window.grin = function (tag) {
  let myField
  tag = ' ' + tag + ' '
  if (
    document.getElementById('comment') &&
    document.getElementById('comment').type == 'textarea'
  ) {
    myField = document.getElementById('comment')
  } else {
    return false
  }
  if (document.selection) {
    myField.focus()
    sel = document.selection.createRange()
    sel.text = tag
    myField.focus()
  } else if (myField.selectionStart || myField.selectionStart == '0') {
    var startPos = myField.selectionStart
    var endPos = myField.selectionEnd
    var cursorPos = endPos
    myField.value =
      myField.value.substring(0, startPos) +
      tag +
      myField.value.substring(endPos, myField.value.length)
    cursorPos += tag.length
    myField.focus()
    myField.selectionStart = cursorPos
    myField.selectionEnd = cursorPos
  } else {
    myField.value += tag
    myField.focus()
  }
}

$('body').on('click', 'a[href^="#toc"],area[href^="#toc"]', function () {
  if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
    let o = $(this.hash),
      i = $('.navbar')[0].offsetHeight
    if (o = o.length && o || $('[name=' + this.hash.slice(1) + ']'), o.length) {
      const a = o.offset().top
      return $('html,body').stop().animate({
        scrollTop: a - i - 5
      }, 1e3), !1
    }
  }
})