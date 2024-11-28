
@if(session('error'))
<script>
    setTimeout(() => {
        Swal.fire({
        toast: true,
        icon: "error",
        title: "Error Occurred!",
        text: "{{session('error')}}",
        position: "top-end",
        showConfirmButton: false,
        timer: 3800,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }

        // ,didClose: () => {
        //     location.reload();
        // }
    });
    }, 400);
</script>
@endif