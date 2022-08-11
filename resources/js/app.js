import './bootstrap'
import '../sass/app.scss'
import '../css/app.css'
import DOMPurify from "isomorphic-dompurify";

window.updateEvent = async function (eventInfo) {
    const token = document.querySelector('meta[name="csrf-token"]').content;
    let res = await fetch(`/ferie/${eventInfo.event.id}`, {
        method: 'POST',
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": token,
        },
        credentials: "same-origin",
        body: JSON.stringify(
            {
                "_token": token,
                "_method": "PUT",
                "id": eventInfo.event.id,
                "start": eventInfo.event.start,
                "end": eventInfo.event.end,
                "old_start": eventInfo.oldEvent.start,
                "old_end": eventInfo.oldEvent.end,
            }
        ),
    })
    let body = await res.json()
    console.log(body)
    let toastEl
    if (res.status === 200)
        toastEl = document.getElementById("success_toast")
    else if (res.status === 401) {
        toastEl = document.getElementById("error_toast")
        eventInfo.revert()
    }

    $(toastEl).find("div.toast-body").html(DOMPurify.sanitize(body.message))
    $('#progressBar').css('width', `${body.perc}%`).text(`${body.perc}% rimasto`)
    $('#hourLeft').text(body.left)
    $('#daysLeft').text((body.left / 8))
    let toast = new bootstrap.Toast(toastEl)
    toast.show()

}

window.unescapeHTML = (text) => {
    return text.replaceAll('&lt;', '<').replaceAll('&gt;', '>')
}
