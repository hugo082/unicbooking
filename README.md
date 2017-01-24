UnicBooking
===========

A Symfony project created on January 6, 2017, 7:30 pm.


Edit d'un book :
    - Problem avec les customers
    - Problem, a l'edit sauvegarder le prix des anciens edit (historique)
    - Problem, sauvegarder les edits

    - Solution : Ne pas sauvegarder les valeurs mais juste les changements.
        Entité avec les même case en mode bool. Permet de contecter et de voir les diffs
