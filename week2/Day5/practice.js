const themeBtn = document.querySelector("#themeBtn");
const body = document.body;


themeBtn.addEventListener("click", () => {
    body.classList.toggle("dark-mode");
    const isDark = body.classList.contains("dark-mode");
    const mode = isDark ? "深色" : "淺色";
    console.log(`切換到${mode}`);
    themeBtn.textContent = isDark ? "深色模式中" : "切換主題";
});