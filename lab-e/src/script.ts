
const msg: string = "Hello!";
alert(msg);

let currentLink: HTMLLinkElement | null = null;
function dynamicznepodlaczenieCSS (href: string): void {

    if (currentLink) {
        currentLink.remove();
    }

    const link: HTMLLinkElement = document.createElement('link');
    link.rel = 'stylesheet';
    link.type = 'text/css';
    link.href = href;
    document.head.appendChild(link);
    currentLink = link;
}

type Style = {
    name: string;
    path: string;
};

const styles: Style[] = [
    { name: 'Styl 1 strony', path: '/style-1.css' },
    { name: 'Styl 2 strony', path: '/style-2.css' },
    { name: 'Styl 3 strony', path: '/style-3.css' },
];
dynamicznepodlaczenieCSS(styles[0].path); //TS automatycznie podłącza do sekcji <head> pierwszy styl ze słownika stylów;

function dynamicznegenerowanielinków (): void {
    const container: HTMLDivElement = document.createElement('div');
    container.id = 'dozmianystylow';
    container.style.padding = '5px';
    container.style.margin = '1px';
    container.style.textAlign = 'center';

    for (const style of styles) {
        const button: HTMLButtonElement = document.createElement('button');
        button.textContent = style.name;
        button.style.margin = '9px';
        button.style.padding = '11px 70px';
        button.style.cursor = 'pointer';
        button.style.backgroundColor = '#ff0000';
        button.style.color = '#ffffff';
        button.setAttribute('data-style', style.path);

        button.addEventListener('click', zmienStyl);
        container.appendChild(button);
    }

    document.body.insertBefore(container, document.body.firstChild);
}


function zmienStyl(event: Event): void {
    const button: HTMLButtonElement = event.target as HTMLButtonElement;
    const stylePath: string | null = button.getAttribute('data-style');

    if (stylePath) {
        dynamicznepodlaczenieCSS(stylePath);
    }
}


if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', (): void => {
        dynamicznegenerowanielinków(); //wywołanie
    });
} else {
    dynamicznegenerowanielinków();
}
