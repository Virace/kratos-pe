/*!
 * Kratos-ajax
 * Virace <Virace@aliyun.com>
 */

// 链接屏蔽
const ignoreList = ['data:', '/wp-', '/rss', '/feed', '/sitemap.xml', '/sitemap.html', '#respond', '#toc_i-', '#toc-', 'javascript:', '.pdf', '.zip', '.rar', '.7z', '.jpg', '.png', '.gif', '.bmp', '#nav'],
  // 替换容器
  container = 'main',
  // 事件列表
  eventList = {
    click: {
      '*:not(#comments-nav)>a[target!=_blank]': function (t) {
        for (let e in ignoreList)
          if (t.indexOf(ignoreList[e]) >= 0) return !0
        return ajax(t, 'pagelink'), !1
      },
      '#comments-nav a': function (t) {
        return ajax(t, 'comtpagenav'), !1
      },
      '.comment-content-link > a': function (t) {
        return ajax(t, 'comment'), !1
      }
    },
    submit: {
      '#searchform': function () {
        return ajax(this.action + '?s=' + $(this).find('#search').val(), 'search'), !1
      }
    }
  }
//  挂载事件
for (let t in eventList)
  for (let f in eventList[t]) {
    $(document.body).on(t, f, function (e) {
      e.preventDefault()
      const a = $(this).attr('href')
      return a ? eventList[t][f](a) : !1
    })
  }

function success (url, msg) {
  //  加载成功后, 滚动条缓动.
  const body = $('body,html')
  let height = $('.navbar')[0].offsetHeight
  if (msg === 'comtpagenav') {

    body.animate({
      scrollTop: $('#comments').offset().top - height
    }, 1e3)
  } else if (msg === 'pagelink' || msg === 'search') {

    url.indexOf('#') === -1 && body.animate({

      scrollTop: $(container).offset().top - height

    }, 1e3)

  } else if (msg === 'comment') {

    const commentId = /#comment-\d+/.exec(url)
    if (commentId.length > 0) {
      const c = $(commentId[0]).offset().top
      $('html,body').animate({
        scrollTop: c - height - 5
      }, 1e3)
    }
  }
}

$(function () {
  window.addEventListener('popstate', function (e) {
    if (e.state) {
      document.title = e.state.title
      $(container).html(e.state.html)
      window.load = window.pjax_reload()
    }
  })
})

function findAll (elems, selector) {
  return elems.filter(selector).add(elems.find(selector))
}

function parseHTML (html) {
  return $.parseHTML(html, document, true)
}

function extractContainer (data) {
  return $(parseHTML(data))
}

function ajax (reqUrl, msg, getData) {
  NProgress.start()
  $.ajax({
    url: reqUrl,
    type: 'GET',
    data: getData,
    beforeSend: function () {
      const document = window.document
      history.replaceState({
        url: document.location.href,
        title: document.title,
        html: $(document).find(container).html()
      }, document.title, document.location.href)
    }, success: function (data) {
      let html = extractContainer(data)
      const body = findAll(html, container).html()
      document.title = findAll(html, 'title').text()

      window.history.pushState({
        url: reqUrl,
        title: findAll(html, 'title').text(),
        html: body
      }, findAll(html, 'title').text(), reqUrl)
      // 动态修改顶部标题. 为以后准备, 现阶段没有用.
      $('.introduce').html(findAll(html, '.introduce').html())

      if (msg === 'pagelink') {

        $(container).html(body)
        const anchor = window.location.hash.substring(location.hash.indexOf('#') + 1)

        anchor && $('body,html').animate({
          scrollTop: $('#' + anchor).offset().top - 61
        }, 600)

      } else if (msg === 'search') {

        $(container).html(body)
        $('#searchform input').val('')

      } else if (msg === 'comtpagenav') {
        $('#comments').html(findAll(html, '#comments').html())

      }
      window.load = window.pjax_reload()
      NProgress.done()
      success(reqUrl, msg)

    }, error: function (n) {
      location.href = reqUrl
    }
  })
}
