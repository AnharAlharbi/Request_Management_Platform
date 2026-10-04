const nameInput = document.getElementById("name");
const emailInput = document.getElementById("email");
const passwordInput = document.getElementById("password");
const officeInput = document.getElementById("office");

const displayName = document.getElementById("display-name");
const displayEmail = document.getElementById("display-email");
const displayOffice = document.getElementById("display-office");
const displayDepartment = document.getElementById("display-department");
const displaypasswoed = document.getElementById("display-password");

// تحميل بيانات المستخدم عند فتح الصفحة
window.onload = function () {
    fetchProfileData();
};

function fetchProfileData() {
    fetch("php/get_profile.php")
        .then(res => res.json())
        .then(response => {
            if (response.success) {
                fillForm(response.user);
                updateDisplay(response.user);
            } else {
                window.location.href = "login.html";
            }
        })
        .catch(err => {
            alert("Error loading profile data");
            console.error(err);
        });
}

// تعبئة النموذج بالقيم الحالية
function fillForm(user) {
    nameInput.value = user.name;
    emailInput.value = user.email;
    passwordInput.value = user.password; // كلمة المرور بدون تشفير
    officeInput.value = user.office_number;
}

// تحديث بيانات العرض في الصفحة
function updateDisplay(user) {
    displayName.textContent = "Name: " + user.name;
    displayEmail.textContent = "Email: " + user.email;
    displayOffice.textContent = "Office Number: " + user.office_number;
    displayDepartment.textContent = "Department: " + (user.department || "—");
     displaypasswoed.textContent = "Password: " + (user.password || "");
}

// إرسال التعديلات عند حفظ النموذج
document.getElementById("profile-form").addEventListener("submit", function(e){
    e.preventDefault();

    const data = {
        name: nameInput.value,
        email: emailInput.value,
        password: passwordInput.value,
        office: officeInput.value
    };

    fetch("php/update_profile.php", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify(data)
    })
    .then(res => res.text()) // لرؤية الأخطاء بسهولة
    .then(text => {
        console.log("Server Response:", text);
        return JSON.parse(text);
    })
    .then(response => {
        if (response.success) {
            alert("Profile updated successfully!");
            fillForm(response.user);
            updateDisplay(response.user);
        } else {
            alert("Error: " + response.message);
        }
    })
    .catch(err => console.error("Update error:", err));
});

// زر تفريغ الحقول
document.getElementById("clear").onclick = () => {
    nameInput.value = "";
    emailInput.value = "";
    passwordInput.value = "";
    officeInput.value = "";
};
