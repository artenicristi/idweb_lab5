$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
})

$(function() {
    getCommentsAjax($('.reclamation')[0].dataset.reclamation)
});

$('.post-btn').on('click', (e) => {

    let commentTextEl = $('#comment');
    let reclamationId = $('.reclamation')[0].dataset.reclamation;

    $.ajax({
        url: `http://127.0.0.1:8000/reclamations/${reclamationId}/comment`,
        data: {'text': commentTextEl.val()},
        type: 'post',
        success: function (response) {
            $('.comments-list').append(buildComment(commentTextEl.val(), 'John', 'Doe', new Date()));
            commentTextEl.val('');
            commentTextEl.next().addClass('d-none');
            commentTextEl.removeClass('is-invalid');
            getCommentsAjax(reclamationId);
        },
        statusCode: {
            400: function () {
                commentTextEl.addClass('is-invalid');
                commentTextEl.next().removeClass('d-none');
            },
            404: function (response) {
                //
            }
        },
    })
});

function getCommentsAjax(reclamationId) {
    $.ajax({
        url: `http://127.0.0.1:8000/reclamations/${reclamationId}/comment`,
        type: 'get',
        success: function (data) {
            $('.comments-list').empty();
            data.forEach(comment => {
                $('.comments-list').append(buildComment(comment.text, comment.nom, comment.prenom, comment.date));
            })
        },
        error: function (result) {
        }
    })
}

function buildComment(text, nom, prenom, date) {
    return `<hr class="my-0" />
            <div class="card-body p-4">
                <div class="d-flex flex-start">
                    <div>
                        <h6 class="fw-bold mb-1">${nom} ${prenom}</h6>
                        <div class="d-flex align-items-center mb-3">
                            <p class="mb-0 status">
                                ${date}
                            </p>
                        </div>
                        <p class="mb-0">
                            ${text}
                        </p>
                    </div>
                </div>
            </div>`
}
