export function changeThemeButtons() {
    let changeThemeButtons = document.querySelectorAll('#theme');
    changeThemeButtons.forEach(button => {
        button.addEventListener('click', function () {
            let theme = this.dataset.theme;
            applyTheme(theme);
        });
    });
}

export function applyTheme(themeName) {
    themeName === "dark" ?
        document.documentElement.setAttribute("data-bs-theme","dark") :
        document.documentElement.setAttribute("data-bs-theme","light");
    localStorage.setItem('theme', themeName);
}

export function setupTheme() {
    let savedTheme = getSavedTheme();
    let systemTheme = getSystemTheme();

    if (savedTheme === null) {
        applyTheme(systemTheme);
        localStorage.removeItem("theme")
        return;
    }
    applyTheme(savedTheme);
}

function getSavedTheme() {
    return localStorage.getItem('theme');
}

function getSystemTheme() {
    let darkScheme = matchMedia('(prefers-color-scheme: dark)').matches;
    return darkScheme ? 'dark' : 'light';
}

