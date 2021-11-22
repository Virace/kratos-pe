window.commentLoad = function (e) {
  const formElector = '#commentform'
  const parentElector = '#commentform input[name=\'comment_parent\']'
  const cancelElector = '#cancel-comment-reply-link'
  const replyLinkElector = '.comment-reply-link'
  const commentListElector = 'ul.list'
  const textareaElector = '#commentform textarea'
  const getParentId = () => $(parentElector).val()
  const setParentId = (value) => $(parentElector).val(value)

  $(formElector).on('submit', function (e) {
    $.ajax({
      url: kratos.ajax_url,
      data: $(this).serialize() + '&action=ajax_comment',
      type: $(this).attr('method'),
      beforeSend: function () {toast.msg('评论正在提交中')},
      error: function (e) {toast.msg(e.responseText)},
      success: function (t) {
        $(textareaElector).each(function () {this.value = ''})
        const pid = getParentId()
        if ('0' !== pid) {
          $('#comment-' + pid).append('<ul class="children">' + t + '</ul>')
        } else {
          if ('asc' === kratos.comment_order) {
            $(commentListElector).append(t)
          } else {
            $(commentListElector).prepend(t)
          }
        }
        toast.msg('评论提交成功')
        const cancel = $(cancelElector)
        cancel.fadeOut(() => {cancel.text('')})
        cancel.click(null)

        setParentId(0)
      }
    })
    return false
  })
  $(replyLinkElector).on('click', function (e) {
    $('html, body').animate({ scrollTop: $(document).height() }, 1e3)
    const parentId = $(this).attr('data-commentid')
    const msg = $(this).attr('data-replyto')

    setParentId(parentId)
    $(cancelElector).text('取消' + msg)
    $(cancelElector).fadeIn()
    return false
  })
  $(cancelElector).on('click', function (e) {
    $(parentElector).val('0')
    $(this).fadeOut(() => {$(this).text('')})
  })
}