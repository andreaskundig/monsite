class Carre {
  constructor(x, y, couleur) {
    this.monX = x;
    this.monY = y;
    this.maCouleur = couleur;
  }

  copie() {
    return new Carre(this.monX, this.monY, this.maCouleur);
  }

  toString() {
    return this.maCouleur;
  }
}

class Figure {
  constructor() {
    this.mesCarres = [];
    this.monId = Math.floor(Math.random() * 10000000000);
    this.maCouleur = null; // Initialize maCouleur
    this.monImage = null;  // Initialize monImage
  }

  copie() {
    const copie = new Figure();
    copie.mesCarres = this.mesCarres.map(carre => carre.copie());
    copie.maCouleur = this.maCouleur;
    return copie;
  }

  egale(figure) {
    if (this === figure) {
      return true; // Corrected: Identity check should return true
    }
    if (this.monId !== figure.monId) {
      return false;
    }
    return true;
  }

  init(x, y, couleur) {
    this.maCouleur = couleur;
    this.mesCarres.push(new Carre(x, y, this.maCouleur));
  }

  grandis(direction) {
    if (!this.mesCarres.length) {
      return false;
    }

    const last = this.mesCarres[this.mesCarres.length - 1];
    const newCarre = new Carre(last.monX + direction[0], last.monY + direction[1], this.maCouleur);

    if (this.monImage) {
      if (!this.monImage.alaPlace(newCarre.monX, newCarre.monY)) {
        return false;
      }
    }

    for (const carre of this.mesCarres) {
      if (newCarre.monX === carre.monX && newCarre.monY === carre.monY) {
        return false;
      }
    }

    this.mesCarres.push(newCarre);
    return true;
  }

  retrecis() {
    if (!this.mesCarres.length) {
      return false;
    }
    if (this.mesCarres.length > 1) {
      this.mesCarres.shift(); // Removes the first element (index 0)
      return true;
    }
    return false;
  }

  avance(direction) {
    const ok = this.grandis(direction);
    if (ok) {
      this.retrecis();
    }
    return ok;
  }

  toString() {
    let result = "";
    for (const carre of this.mesCarres) {
      result += `[${carre.monX},${carre.monY},${carre.maCouleur.substring(0, 1)}]`;
    }
    return result;
  }

  transforme(index, direction) {
    if (index < 1) {
      return this.grandis(direction);
    } else {
      return this.retrecis();
    }
  }

  directions() {
    return [[0, 1], [1, 0], [-1, 0], [0, -1]];
  }
}

class Image {
  constructor(x, y) {
    this.mesFigures = [];
    this.monX = x;
    this.monY = y;
  }

  copie() {
    const copie = new Image(this.monX, this.monY);
    copie.mesFigures = this.mesFigures.map(figure => figure.copie());
    return copie;
  }

  alaPlace(x, y) {
    if (x < 0 || x >= this.monX) {
      return false;
    }
    if (y < 0 || y >= this.monY) {
      return false;
    }

    if (this.mesFigures.length > 0) {
      for (const figure of this.mesFigures) {
        for (const carre of figure.mesCarres) {
          if (x === carre.monX && y === carre.monY) {
            return false;
          }
        }
      }
    }
    return true;
  }

  figureAlaPlace(uneFigure) {
      if (this.mesFigures.length > 0) {
          for (const figure of this.mesFigures) {
              if (figure.egale(uneFigure)) {
                  return true;
              }
          }
      }

      for (const carre of uneFigure.mesCarres) {
          if (!this.alaPlace(carre.monX, carre.monY)) {
              return false;
          }
      }
      return true;
  }

  ajouteFigure(figure) {
    let ok = true;
    for (const carre of figure.mesCarres) {
      ok = ok && this.alaPlace(carre.monX, carre.monY);
    }
    if (ok) {
      figure.monImage = this;
      this.mesFigures.push(figure);
    }
    return ok;
  }

  represente() {
    const rep = Array(this.monX).fill(null).map(() => Array(this.monY).fill(0)); // Initialize 2D array

    for (const figure of this.mesFigures) {
      for (const carre of figure.mesCarres) {
        rep[carre.monX][carre.monY] = carre;
      }
    }
    return rep;
  }

  toString() {
    let result = "";
    const rep = this.represente();
    for (let i = 0; i < this.monX; i++) {
      result += "|";
      for (let j = 0; j < this.monY; j++) {
        if (rep[i][j]) {
          let couleur = rep[i][j].maCouleur.substring(0, 1);
          if (!couleur) {
            couleur = "X";
          }
          result += couleur;
        } else {
          result += "_";
        }
      }
      result += "|<br>";
    }
    return result;
  }
}

class Algorithme {
  suivante(imageOriginale) {
    const image = imageOriginale.copie();

    for (let i = 0; i < image.mesFigures.length; i++) {
      const figure = image.mesFigures[i];
      const transformations = [0, 1];

      while (transformations.length > 0) {
        const max = transformations.length - 1;
        const transnb = max > 0 ? Math.floor(Math.random() * (max + 1)) : 0;
        const transformation = transformations[transnb];
        const directions = figure.directions();

        while (directions.length > 0) {
          const maxDir = directions.length - 1;
          const dirnb = maxDir > 0 ? Math.floor(Math.random() * (maxDir + 1)) : 0;
          const direction = directions[dirnb];

          if (figure.transforme(transformation, direction)) {
            return image; // Break out of both loops
          }

          directions.splice(dirnb, 1); // Remove the direction
        }

        transformations.splice(transnb, 1); // Remove the transformation
      }
    }

    return image;
  }
}

class Hasard {
  choisisPoints(x, y, nombre) {
    let points = [];
    for (let i = 0; i < x; i++) {
      for (let j = 0; j < y; j++) {
        points.push([i, j]);
      }
    }

    let choisis = [];
    for (let i = 0; i < nombre; i++) {
      const max = points.length - 1;
      let numero = max;
      if (max > 0) {
        numero = Math.floor(Math.random() * (max + 1));
      }
      choisis.push(points[numero]);
      points.splice(numero, 1);
    }
    return choisis;
  }

  genereProduit(nb, maxproduit) {
    maxproduit = Math.floor(Math.random() * maxproduit) + 1; // Ensure maxproduit is an integer
    let aremplir = [];
    let produit = 1;

    for (let i = 0; i < nb; i++) {
      if (maxproduit === 1) {
        aremplir[i] = 1;
      } else if (maxproduit < 1) {
        aremplir[i] = 0;
      } else {
        aremplir[i] = Math.floor(Math.random() * maxproduit) + 1; // Ensure generated values are integers
      }
      produit *= aremplir[i];
    }

    if (produit <= maxproduit) {
      return aremplir;
    }

    let echelle = Math.pow(maxproduit / produit, 1 / nb);
    let test = 1;
    for (let i = 0; i < nb; i++) {
      aremplir[i] = Math.floor(Math.max(1, aremplir[i] * echelle));
      test *= aremplir[i];
    }

    while (test > maxproduit) {
      for (let i = 0; i < nb; i++) {
        if (aremplir[i] > 1) {
          aremplir[i] -= 1;
          test = 1;
          for (let j = 0; j < nb; j++) { // Corrected loop variable to 'j'
            test *= aremplir[j];
          }
          if (test <= maxproduit) {
            break; // Exit inner loop
          }
        }
      }
      if (test <= maxproduit) break; // Exit outer loop if test <= maxproduit
    }

    return aremplir;
  }
}

class Page {
  constructor(x, y) {
    this.monMaxX = x;
    this.monMaxY = y;
    this.monAlgorithme = new Algorithme();
    this.monHasard = new Hasard();
    this.mesImages = []; // Initialize mesImages
    this.maLongueurCarre = 0;
    this.monEspaceParCarre = 0.5;
    this.monNbCarreCaseX = 0;
    this.monNbCarreCaseY = 0;
    this.monNbFigures = 0;
    this.monMaxFigures = 0;
    this.mesCouleurs = ["black", "gray", "red", "yellow"];
  }

  longueurTotaleX() {
    return this.longueurTotale(
      this.maLongueurCarre,
      this.monNbImagesX,
      this.monNbCarreCaseX,
      this.monEspaceParCarre
    );
  }

  longueurTotaleY() {
    return this.longueurTotale(
      this.maLongueurCarre,
      this.monNbImagesY,
      this.monNbCarreCaseY,
      this.monEspaceParCarre
    );
  }

  longueurTotale(longueurCarre, nbImages, nbCarreCase, espaceParCarre) {
    return (
      longueurCarre * nbImages * nbCarreCase +
      longueurCarre * espaceParCarre * (nbImages - 1)
    );
  }

  suivante() {
    const suivante = new Page(this.monMaxX, this.monMaxY);
    suivante.maLongueurCarre = this.maLongueurCarre;
    suivante.monNbImagesX = this.monNbImagesX;
    suivante.monNbCarreCaseX = this.monNbCarreCaseX;
    suivante.monNbImagesY = this.monNbImagesY;
    suivante.monNbCarreCaseY = this.monNbCarreCaseY;
    suivante.monNbFigures = this.monNbFigures;
    suivante.monMaxFigures = this.monMaxFigures;
    suivante.mesCouleurs = this.mesCouleurs;

    const image = this.mesImages[this.monNbImagesX - 1]?.[this.monNbImagesY - 1]; // Use optional chaining
    const nextImage = suivante.monAlgorithme.suivante(image);
    suivante.remplisAvecImage(nextImage);
    return suivante;
  }

  remplis() {
    // Chooses height and width randomly if necessary
    // Taking into account the maximum dimensions
    let reduits = this.reduis(
      this.maLongueurCarre,
      this.monNbImagesY,
      this.monNbCarreCaseY,
      this.monMaxY
    );
    this.maLongueurCarre = reduits[0];
    this.monNbImagesY = reduits[1];
    this.monNbCarreCaseY = reduits[2];

    reduits = this.reduis(
      this.maLongueurCarre,
      this.monNbImagesX,
      this.monNbCarreCaseX,
      this.monMaxX
    );
    this.maLongueurCarre = reduits[0];
    this.monNbImagesX = reduits[1];
    this.monNbCarreCaseX = reduits[2];

    // Chooses the number of figures randomly
    if (this.monNbFigures === 0) {
      const max = Math.ceil(
        Math.min(this.monMaxFigures, this.monNbCarreCaseY * this.monNbCarreCaseX)
      );
      this.monNbFigures = max > 1 ? Math.floor(Math.random() * max) + 1 : 1;
    }

    // Creates the starting image
    const image = new Image(this.monNbCarreCaseX, this.monNbCarreCaseY);
    const choisis = this.monHasard.choisisPoints(
      this.monNbCarreCaseX,
      this.monNbCarreCaseY,
      this.monNbFigures
    );

    for (let i = 0; i < this.monNbFigures; i++) {
      const couleur = this.mesCouleurs[i % this.mesCouleurs.length];
      const choisi = choisis[i];
      const figure = new Figure();
      figure.init(choisi[0], choisi[1], couleur);
      image.ajouteFigure(figure);
    }

    // Generates the other images
    this.remplisAvecImage(image);
  }

  remplisAvecImage(image) {
    for (let i = 0; i < this.monNbImagesX; i++) {
      this.mesImages[i] = this.mesImages[i] || [];  // Ensure row exists
      for (let j = 0; j < this.monNbImagesY; j++) {
        this.mesImages[i][j] = image.copie(); // Store a copy
        image = this.monAlgorithme.suivante(image); // Evolve the original
      }
    }
  }

  toString() {
    let result = "";
    for (let i = 0; i < this.monNbImagesX; i++) {
      for (let j = 0; j < this.monNbImagesY; j++) {
        result += `${i},${j}<br>${this.mesImages[i][j].toString()}<br>`;
      }
    }
    return result;
  }

  reduis(longueurCarre, nbImages, nbCarreCase, max) {
    let tableau = [longueurCarre, nbImages, nbCarreCase];

    let vides = [];
    let nonNuls = [];
    let produitNonNuls = 1;

    for (let i = 0; i < tableau.length; i++) {
      let valeur = tableau[i];
      if (valeur === 0) {
        vides.push(i);
      } else {
        nonNuls.push(i);
        produitNonNuls *= valeur;
      }
    }

    if (vides.length > 0) {
      let generes = this.monHasard.genereProduit(
        vides.length,
        max / produitNonNuls
      );
      for (let i = 0; i < vides.length; i++) {
        tableau[vides[i]] = generes[i];
      }
    }

    let index = 0;
    while (
      this.longueurTotale(
        tableau[0],
        tableau[1],
        tableau[2],
        this.monEspaceParCarre
      ) > max
    ) {
      while (tableau[index] <= 1) {
        index++;
        if (index >= tableau.length) {
          break; // Exit while loop
        }
      }

      if (index >= tableau.length) break; // Exit outer while loop

      tableau[index] -= 1;
    }

    return tableau;
  }
}

// Export the classes so they can be used in other files
export { Carre, Figure, Image, Algorithme, Hasard, Page };
