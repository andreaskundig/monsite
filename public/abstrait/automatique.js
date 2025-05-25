import { Carre, Figure, Image, Algorithme, Hasard, Page } from './figure.js';

document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const figureContainer = document.getElementById('figureContainer');
    const debugContainer = document.getElementById('debugContainer');

    function getFormValues() {
        return {
            lg: parseInt(document.querySelector('input[name="lg"]').value),
            px: parseInt(document.querySelector('input[name="px"]').value),
            ix: parseInt(document.querySelector('input[name="ix"]').value),
            py: parseInt(document.querySelector('input[name="py"]').value),
            iy: parseInt(document.querySelector('input[name="iy"]').value),
            nb: parseInt(document.querySelector('input[name="nb"]').value)
        };
    }

    function generateFigures(params) {
        const { lg, px, ix, py, iy, nb } = params;

        const unite = "px";
        const minlg = 4;
        const maxx = 550 / minlg;
        const maxy = 850 / minlg;

        const couleurs = [
            "black", "maroon", "green", "olive", "navy", "purple", "teal", "silver",
            "gray", "red", "lime", "yellow", "blue", "fuchsia", "aqua"
        ];

        const page = new Page(maxx, maxy);
        page.maLongueurCarre = lg / minlg;
        page.monNbImagesX = px;
        page.monNbCarreCaseX = ix;
        page.monNbImagesY = py;
        page.monNbCarreCaseY = iy;
        page.monNbFigures = nb;
        page.monMaxFigures = 4;
        page.mesCouleurs = [
            "black", "red", "blue", "green", "silver",
            "gray", "maroon", "navy", "olive", "purple", "teal", "yellow"
        ];

        page.remplis();

        const espace = Math.max(3, lg / 2);
        const marge = 130;

        let html = '';
        const imgs = page.mesImages;

        for (let i = 0; i < imgs.length; i++) {
            for (let j = 0; j < imgs[i].length; j++) {
                const unecase = imgs[i][j].represente();

                html += `
                    <div style="position:absolute;
                                top:${i * (ix * lg + espace)}${unite};
                                left:${j * (iy * lg + espace)}${unite};
                                width:${iy * lg}${unite};
                                height:${ix * lg}${unite};
                                border:solid 1px black;">
                `;

                for (let k = 0; k < unecase.length; k++) {
                    for (let l = 0; l < unecase[k].length; l++) {
                        if (unecase[k][l]) {
                            html += `
                                <div style="position:absolute;
                                            top:${k * lg}${unite};
                                            left:${l * lg}${unite};
                                            width:${lg}${unite};
                                            height:${lg}${unite};
                                            background-color:${unecase[k][l].maCouleur}">
                                </div>
                            `;
                        }
                    }
                }
                html += '</div>';
            }
        }

        figureContainer.innerHTML = html;
        // Optionally display debug info
        //debugContainer.innerText = debug;

    }


    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the form from actually submitting
        const params = getFormValues();
        generateFigures(params);
    });

    // Initial figure generation on page load
    generateFigures(getFormValues()); // Generate figures with initial form values

    // Add event listeners to the "random choice" links.
    const randomLinks = document.querySelectorAll('a[href^="automatique.php"]'); // Selects all the links.
    randomLinks.forEach(link => {
        link.addEventListener('click', function(event){
            event.preventDefault();

            let url = new URL(this.href, window.location.href); // Convert relative URL to an absolute one, so that parsing works consistently
            let searchParams = new URLSearchParams(url.search);

            let nb = searchParams.get("nb");
            let lg = searchParams.get("lg");
            let ix = searchParams.get("ix");
            let iy = searchParams.get("iy");
            let px = searchParams.get("px");
            let py = searchParams.get("py");

            // If the link contains a value for the parameter, use it.
            // Otherwise, use the existing value.
            let params = {
                lg: lg ? parseInt(lg) : parseInt(document.querySelector('input[name="lg"]').value),
                px: px ? parseInt(px) : parseInt(document.querySelector('input[name="px"]').value),
                ix: ix ? parseInt(ix) : parseInt(document.querySelector('input[name="ix"]').value),
                py: py ? parseInt(py) : parseInt(document.querySelector('input[name="py"]').value),
                iy: iy ? parseInt(iy) : parseInt(document.querySelector('input[name="iy"]').value),
                nb: nb ? parseInt(nb) : parseInt(document.querySelector('input[name="nb"]').value)
            };
            generateFigures(params);
        })
    });
});
