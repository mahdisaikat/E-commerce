(() => {
    const THEME = 'coreui-free-bootstrap-admin-template-theme'

    const getStoredTheme = () => localStorage.getItem(THEME)
    const setStoredTheme = theme => localStorage.setItem(THEME, theme)

    const getPreferredTheme = () => {
        const storedTheme = getStoredTheme()
        if (storedTheme) return storedTheme
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
    }

    const setTheme = theme => {
        if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.setAttribute('data-coreui-theme', 'dark')
        } else {
            document.documentElement.setAttribute('data-coreui-theme', theme)
        }

        const event = new Event('ColorSchemeChange')
        document.documentElement.dispatchEvent(event)
    }

    const showActiveTheme = theme => {
        const btnToActive = document.querySelector(`[data-coreui-theme-value="${theme}"]`)
        const themeIcon = document.getElementById('theme-icon')

        for (const element of document.querySelectorAll('[data-coreui-theme-value]')) {
            element.classList.remove('active')
        }

        if (btnToActive) btnToActive.classList.add('active')

        // Update the shared icon
        if (themeIcon) {
            themeIcon.className = 'fas fa-lg' // reset classes
            if (theme === 'light') {
                themeIcon.classList.add('fa-sun')
            } else if (theme === 'dark') {
                themeIcon.classList.add('fa-moon')
            } else {
                themeIcon.classList.add('fa-circle-half-stroke')
            }
        }
    }

    setTheme(getPreferredTheme())

    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
        const storedTheme = getStoredTheme()
        if (storedTheme !== 'light' && storedTheme !== 'dark') {
            setTheme(getPreferredTheme())
            showActiveTheme(getPreferredTheme())
        }
    })

    window.addEventListener('DOMContentLoaded', () => {
        showActiveTheme(getPreferredTheme())

        for (const toggle of document.querySelectorAll('[data-coreui-theme-value]')) {
            toggle.addEventListener('click', () => {
                const theme = toggle.getAttribute('data-coreui-theme-value')
                setStoredTheme(theme)
                setTheme(theme)
                showActiveTheme(theme)
            })
        }
    })
})()
