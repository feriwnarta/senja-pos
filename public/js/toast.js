class Toast {
    static showSuccess(element, title, message) {
        let styleHeader = `
        font-family: 'Poppins';
        font-style: normal;
        font-weight: 500;
        font-size: 20px;
        line-height: 28px;
        color: #0A0A0A;
        `;

        let styleBody = `
        font-family: 'Poppins';
        font-style: normal;
        font-weight: 400;
        font-size: 14px;
        line-height: 20px;
        padding: 16px 16px;
        gap: 4px;
        color: #616161;
        `;

        let html = `
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

        <div class="toast-container position-fixed top-0 start-50 translate-middle-x py-5">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true"
        >
          <div class="toast-header">

            <img src="/img/icons/toast/check-circle.png" class="rounded me-2" alt="...">

            <strong class="me-auto" style="${styleHeader}">
            ${title}
            </strong>

            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
          <div class="toast-body" style="${styleBody}">
            ${message}

          </div>

          <div style=
            "
            width: 100%;
              height: 0px;

              border: 2px solid #B8DBCA;
            ">
            </div>
        </div>
      </div>
        `;
        $(element).append(html);

        new bootstrap.Toast($("#liveToast")).show();
    }

    static showError(element, title, message) {
        let styleHeader = `
      font-family: 'Poppins';
      font-style: normal;
      font-weight: 500;
      font-size: 20px;
      line-height: 28px;
      background: var(--danger-surface, #FFF4F2);
      color: #0A0A0A;
      `;

        let styleBody = `
      font-family: 'Poppins';
      font-style: normal;
      font-weight: 400;
      font-size: 14px;
      line-height: 20px;
      padding: 16px 16px;
      background: var(--danger-surface, #FFF4F2);
      gap: 4px;
      color: #616161;
      `;

        let html = `
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

      <div class="toast-container position-fixed top-0 start-50 translate-middle-x py-5">
      <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true"
      >
        <div class="toast-header" style="${styleHeader}">

          <img src="/img/icons/toast/exclamation-circle-danger.png" class="rounded me-2" alt="" width="24" height="24">

          <strong class="me-auto">
          ${title}
          </strong>

          <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" style="${styleBody}">
          ${message}

        </div>

        <div style=
          "
          width: 100%;
            height: 0px;

            border: 2px solid #EEB4B0;
          ">
          </div>
      </div>
    </div>
      `;
        $(element).append(html);

        new bootstrap.Toast($("#liveToast")).show();
    }

    static showInfo(element, title, message) {
        let styleHeader = `
    font-family: 'Poppins';
    font-style: normal;
    font-weight: 500;
    font-size: 20px;
    line-height: 28px;
    background: var(--info-surface, #F0F3FF);
    color: #0A0A0A;
    `;

        let styleBody = `
    font-family: 'Poppins';
    font-style: normal;
    font-weight: 400;
    font-size: 14px;
    line-height: 20px;
    padding: 16px 16px;
    background: var(--info-surface, #F0F3FF);
    gap: 4px;
    color: #616161;
    `;

        let html = `
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <div class="toast-container position-fixed top-0 start-50 translate-middle-x py-5">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true"
    >
      <div class="toast-header" style="${styleHeader}">

        <img src="/img/icons/toast/information-circle.png" class="rounded me-2" alt="" width="24" height="24">

        <strong class="me-auto">
        ${title}
        </strong>

        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body" style="${styleBody}">
        ${message}

      </div>

      <div style=
        "
        width: 100%;
          height: 0px;

          border: 2px solid #B1C5F6;
        ">
        </div>
    </div>
  </div>
    `;
        $(element).append(html);

        new bootstrap.Toast($("#liveToast")).show();
    }

    static showConfirm(element, title, message, onYes, onNo) {
        let styleHeader = `
  font-family: 'Poppins';
  font-style: normal;
  font-weight: 500;
  font-size: 20px;
  line-height: 28px;
  background: var(--warning-surface, #FFF9F2);
  color: #0A0A0A;
  `;

        let styleBody = `
  font-family: 'Poppins';
  font-style: normal;
  font-weight: 400;
  font-size: 14px;
  line-height: 20px;
  padding: 16px 16px;
  background: var(--warning-surface, #FFF9F2);
  gap: 4px;
  color: #616161;
  `;

        let html = `
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

  <div class="toast-container position-fixed top-0 start-50 translate-middle-x py-5">
  <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true"
  >
    <div class="toast-header" style="${styleHeader}">

      <img src="/img/icons/toast/exclamation-circle.png" class="rounded me-2" alt="" width="24" height="24">

      <strong class="me-auto">
      ${title}
      </strong>

      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body" style="${styleBody}">
      ${message}



      <div class="d-flex flex-row mt-3 justify-content-end">
        <button type="button" class="btn btn-primary"
        style=
        "
        display: flex;
        padding: 6px 16px;
        justify-content: center;
        align-items: center;
        gap: 4px;
        flex-shrink: 0;

        color: var(--warning-main, #CD7B2E);
        text-align: center;
        /* Button */
        font-size: 14px;
        font-family: Poppins;
        font-weight: 500;
        line-height: 20px;
        border: none;
        border-radius: 6px;
        border: 1px solid var(--warning-border, #EECEB0);
        background: var(--warning-surface, #FFF9F2);
        /* Shadow 1 */
        box-shadow: 0px 1px 2px 0px rgba(0, 0, 0, 0.12);
        "
        onclick="(
          ${onNo})()">
        Tidak
        </button>
        <button type="button" class="btn btn-secondary ms-2"
        style="
          display: flex;
          padding: 6px 16px;
          justify-content: center;
          align-items: center;
          gap: 4px;
          flex-shrink: 0;

          color: var(--neutral-10, #FFF);
          text-align: center;
          /* Button */
          font-size: 14px;
          font-family: Poppins;
          font-weight: 500;
          line-height: 20px;
          border: none;
          border-radius: 6px;
          background: var(--warning-main, #CD7B2E);
          /* Shadow 1 */
          box-shadow: 0px 1px 2px 0px rgba(0, 0, 0, 0.12);

        "
        onclick="(
          ${onYes})()">
        Ya
        </button>
      </div>
    </div>

    <div style=
      "
      width: 100%;
        height: 0px;

        border: 2px solid #EECEB0;
      ">
      </div>
  </div>
</div>
  `;
        $(element).append(html);

        new bootstrap.Toast($("#liveToast")).show();

    }

}
