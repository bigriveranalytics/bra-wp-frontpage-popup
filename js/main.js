/*
 * BRA Frontpage Popup 2024
 *
 * Javascript for a Wordpress popup plugin.
 */

const LOCAL_STORAGE_ID = 'entry_protocol_accepted'

document.addEventListener('DOMContentLoaded', function () {
  const dialog = document.querySelector('#fpp-modal')
  const acceptBtn = dialog.querySelector('#fpp-accept')

  function showDialog() {
    if (dialog && typeof dialog.showModal === 'function') {
      dialog.showModal()
    } else {
      dialog.style.display = 'block'
    }
  }
  // Check localStorage
  const accepted = localStorage.getItem(LOCAL_STORAGE_ID)
  const lastAccepted = accepted ? new Date(accepted) : null
  const now = new Date()

  const threeDays = 3 * 24 * 60 * 60 * 1000

  if (!lastAccepted || now - lastAccepted > threeDays) {
    showDialog()
  } else {
    dialog.style.display = 'none'
  }

  // When the accept button is clicked
  acceptBtn.addEventListener('click', function () {
    const acceptedOnDate = new Date().toISOString()
    localStorage.setItem(LOCAL_STORAGE_ID, acceptedOnDate)

    dialog.close()
    dialog.classList.add('closed')
  })

  // declineBtn.addEventListener('click', function () {
  //   const url = declineBtn.dataset.destination
  //   if (!url) throw new Error('No destination URL found on decline button')

  //   if (
  //     confirm(
  //       'Are you sure you want to decline? You will be redirected from this website.'
  //     )
  //   ) {
  //     window.location.href = url
  //   }
  // })
})
