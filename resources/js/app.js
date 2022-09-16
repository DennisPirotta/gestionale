import './bootstrap'
import '../sass/app.scss'
import '../css/app.css'
import DOMPurify from "isomorphic-dompurify";

const token = document.querySelector('meta[name="csrf-token"]').content;

window.updateEvent = async function (eventInfo) {
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
                "start": moment(eventInfo.event.start).add(1,"d"),
                "end": moment(eventInfo.event.end).add(1,"d"),
                "old_start": eventInfo.oldEvent.start,
                "old_end": eventInfo.oldEvent.end,
                "allDay": eventInfo.el.fcSeg.eventRange.def.allDay
            }
        ),
    })
    let body = await res.json()
    await notification(body,res.status)
    $('#hourLeft').text(body.left)
    $('#daysLeft').text((body.left / 8))
    return body.perc / 100
}

window.unescapeHTML = (text) => {
    return text.replaceAll('&lt;', '<').replaceAll('&gt;', '>')
}

async function notification(body,status){
    let toastEl
    if (status === 200)
        toastEl = document.getElementById("success_toast")
    else if (status === 401) {
        toastEl = document.getElementById("error_toast")
        eventInfo.revert()
    }
    $(toastEl).find("div.toast-body").html(DOMPurify.sanitize(body.message))
    let toast = new bootstrap.Toast(toastEl)
    toast.show()
}
