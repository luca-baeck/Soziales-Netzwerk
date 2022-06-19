
function setTheme(){
   var theme = localStorage.getItem('theme');
   let root = document.documentElement;
   switch(theme) {
    case 'light theme':
        root.style.setProperty('--background-color', '#d8dcd6');
        root.style.setProperty('--header-color', '#e5e5e5');
        root.style.setProperty('--icon-color', '#000');
        root.style.setProperty('--icon-color-reversed', '#fff');
        root.style.setProperty('--text-color', '#000');
        root.style.setProperty('--logo-color1', '#363737');
        root.style.setProperty('--logo-color2', '#59656d');
        root.style.setProperty('--logo-color3', '#fff9d0');
        break;
    case 'dark theme':
        root.style.setProperty('--background-color', '#000');
        root.style.setProperty('--header-color', '#181818');
        root.style.setProperty('--icon-color', '#d8d8d8');
        root.style.setProperty('--icon-color-reversed', '#000');
        root.style.setProperty('--text-color', '#d8d8d8');
        root.style.setProperty('--logo-color1', '#3700B3');
        root.style.setProperty('--logo-color2', '#a864fb');
        root.style.setProperty('--logo-color3', '#5f1e29');
        break;
    case 'warm theme':
        root.style.setProperty('--background-color', '#121212');
        root.style.setProperty('--header-color', '#102027');
        root.style.setProperty('--icon-color', '#fff');
        root.style.setProperty('--icon-color-reversed', '#000');
        root.style.setProperty('--text-color', '#fff');
        root.style.setProperty('--logo-color1', '#6f3199');
        root.style.setProperty('--logo-color2', '#833AB4');
        root.style.setProperty('--logo-color3', '#FCAF45');
        break;
    default:
        root.style.setProperty('--background-color', '#121212');
        root.style.setProperty('--header-color', '#102027');
        root.style.setProperty('--icon-color', '#fff');
        root.style.setProperty('--icon-color-reversed', '#000');
        root.style.setProperty('--text-color', '#fff');
        root.style.setProperty('--logo-color1', '#0cbc5e');
        root.style.setProperty('--logo-color2', '#54ac6b');
        root.style.setProperty('--logo-color3', '#2cb1cb');

  }
}

setTheme();