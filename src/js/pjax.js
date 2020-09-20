/*!
 * Kratos-ajax
 * Virace <Virace@aliyun.com>
 */

const ignore = ['data:', '/wp-', '/rss', '/feed', '/sitemap.xml', '/sitemap.html', '#respond', '#toc_i-', '#toc-', 'javascript:', '.pdf', '.zip', '.rar', '.7z', '.jpg', '.png', '.gif', '.bmp', '#nav'];

const selectorContent = 'section';

const elements = {
    'click': {
        '*:not(#comments-nav)>a[target!=_blank]': function (uri) {
            for (let i in ignore) {
                if (uri.indexOf(ignore[i]) >= 0) {
                    return true;
                }
            }
            ajax(uri, 'pagelink');
            return false;
        },
        '#comments-nav a': function (uri) {
            ajax(uri, 'comtpagenav');
            return false;
        }
    },
    'submit': {
        '#searchform': function () {
            ajax(this.action + '?s=' + $(this).find('#search').val(), 'search');
            return false;
        }
    },
}

for (let event in elements) {
    for (let selector in elements[event]) {
        $(document).on(event, selector, function () {
            const uri = $(this).attr('href');
            if (!uri) return false;
            return elements[event][selector](uri);
        })
    }

}

function moveScroll(reqUrl, msg) {
    const htmlSelector = $('body,html');

    $(selectorContent).fadeTo('fast', 0.7);

    if (msg === 'comtpagenav') {
        // 评论
        htmlSelector.animate({scrollTop: $('#comments').offset().top - 70}, 400);
    } else if (msg === 'pagelink' || msg === 'search') {
        // 搜索以及其他
        if (reqUrl.indexOf('#') === -1) {
            htmlSelector.animate({scrollTop: $('.k-main').offset().top - $('.k-nav')[0].offsetHeight}, 400);
        }
    }
}

$(function () {
    // 监听有变化修改并重新初始化一些东西
    window.addEventListener('popstate', function (e) {
        if (e.state) {
            document.title = e.state.title;
            $(selectorContent).html(e.state.html);

            window.load = window.pjax_reload();
        }
    })
})

function findAll(elems, selector) {
    return elems.filter(selector).add(elems.find(selector));
}

function parseHTML(html) {
    return $.parseHTML(html, document, true);
}

function format(data) {
    // let fullDocument = /<html/i.test(data), $body;
    // if (fullDocument) {
    //     $body = $(parseHTML(data.match(/<body[^>]*>([\s\S.]*)<\/body>/i)[0]));
    // } else {
    //     $body = $(parseHTML(data));
    // }
    return $(parseHTML(data));
}

function ajax(reqUrl, msg, getData) {
    NProgress.start();
    moveScroll(reqUrl, msg)
    $.ajax({
        url: reqUrl,
        type: 'GET',
        data: getData,
        beforeSend: function () {
            const doc = window.document
            history.replaceState({
                url: doc.location.href,
                title: doc.title,
                html: $(document).find(selectorContent).html(),
            }, doc.title, document.location.href)
        },
        success: function (data) {
            let body = format(data);

            const html = findAll(body, selectorContent).html();
            document.title = findAll(body, 'title').text();
            window.history.pushState({
                url: reqUrl,
                title: findAll(body, 'title').text(),
                html: html
            }, findAll(body, 'title').text(), reqUrl);
            $('.introduce').html(findAll(body, '.introduce').html())
            if (msg === 'pagelink') {
                $(selectorContent).html(html);
                const anchor = window.location.hash.substring(location.hash.indexOf('#') + 1);
                if (anchor) {
                    $('body,html').animate({scrollTop: $('#' + anchor).offset().top - 61}, 600);
                }
                try {
                    $('pre>code').each(function () {
                        hljs.highlightBlock(this)
                    })
                } catch (err) {
                }
            } else if (msg === 'search') {
                $(selectorContent).html(html);
                $('#searchform').animate({width: '0'}, 200);
                $('#searchform input').val('');
            } else if (msg === 'comtpagenav') {
                $('#comments').html(findAll(body, '#comments').html());
                $('#comments-nav').html(findAll(body, '#comments-nav').html());
            }
            $(selectorContent).fadeTo('fast', 1);
            window.load = window.pjax_reload();
            NProgress.done();
        },
        error: function (request) {
            $(selectorContent).fadeTo('fast', 1);
            location.href = reqUrl;
        }
    })
}
