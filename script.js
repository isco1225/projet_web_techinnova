document.addEventListener("DOMContentLoaded", function () {
    const btn_conn = document.getElementById("conn");
    const btn_inscript = document.getElementById("inscript");

    btn_conn.addEventListener('click', ()=>{
        window.location.href = "connexions/page_connexion.php";
    })

    btn_inscript.addEventListener('click', ()=>{
        window.location.href = "connexions/page_inscription.php";
    })
});


