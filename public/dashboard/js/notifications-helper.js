function successMessage(message) {
    Swal.fire({
        title: "Good Job",
        text: message,
        icon: "success",
        customClass: {
            confirmButton: "btn btn-primary",
        },
        buttonsStyling: false,
    });

    $(".invalid-feedback").each(function () {
        $(this).remove();
    });
    $(".is-invalid").each(function () {
        $(this).removeClass("is-invalid");
    });
}

function errorMessage(message) {
    Swal.fire({
        title: "Error",
        text: message,
        icon: "error",
        customClass: {
            confirmButton: "btn btn-primary",
        },
        buttonsStyling: false,
    });
}

function displayErrors(response, editMethod) {
    let errorsList = JSON.parse(response.responseText).errors;
    //remove all elements that has invalid class within (to clear the errors list)
    $(".is-invalid").each(function () {
        $(this).removeClass("is-invalid");
    });
    $(".invalid-feeback").each(function () {
        $(this).remove();
    });
    if (!editMethod) {
        for (const [key, value] of Object.entries(errorsList)) {
            $(`[name="${key}"]`).removeClass("is-invalid");
            let error = `<div class="invalid-feedback">${value}</div>`;
            $(`[name="${key}"]`).after(error);
            $(`[name="${key}"]`).addClass("is-invalid");
        }
    } else {
        for (const [key, value] of Object.entries(errorsList)) {
            $(`[name="edit_${key}"]`).removeClass("is-invalid");
            $(`[name="edit_${key}"]`).next().remove();
            let error = `<div class="invalid-feedback">${value}</div>`;
            $(`[name="edit_${key}"]`).after(error);
            $(`[name="edit_${key}"]`).addClass("is-invalid");
        }
    }
}
