#Schéma relationnel de la base de données du CMS

![http://www.opixido.com/ocms/screen/ocms-structure2.png](http://www.opixido.com/ocms/screen/ocms-structure2.png)

#Base de donnée

![http://www.opixido.com/ocms/screen/droits.png](http://www.opixido.com/ocms/screen/droits.png)

![http://www.opixido.com/ocms/screen/textes.png](http://www.opixido.com/ocms/screen/textes.png)

![http://www.opixido.com/ocms/screen/plug.png](http://www.opixido.com/ocms/screen/plug.png)



# s\_admin #
Commentaires sur la table: InnoDB free: 11264 kB

Champ |Type |Null |Défaut ||Commentaires|
|:----|:----|:-------|
admin\_id |smallint(5) |Non |  |
admin\_login |varchar(64) |Non |  |
admin\_nom |varchar(255) |Non |  |
admin\_pwd |varchar(255) |Non |  |
admin\_email |varchar(255) |Non |  | |
admin\_last\_cx |datetime |Non |0000-00-00 00:00:00 |

# s\_admin\_role #
Commentaires sur la table: InnoDB free: 11264 kB

Champ |Type |Null |Défaut ||Commentaires|
|:----|:----|:-------|
fk\_admin\_id |int(10) |Non |0 |
fk\_role\_id |int(10) |Non ||0|

# s\_admin\_rows #
Commentaires sur la table: InnoDB free: 11264 kB

Champ |Type |Null |Défaut ||Commentaires|
|:----|:----|:-------|
fk\_admin\_id |int(10) |Non |0 |
fk\_row\_id |int(10) |Non |0 |
fk\_table |varchar(64) |Non |  |

# s\_admin\_trad #
Champ |Type |Null |Défaut ||Commentaires|
|:----|:----|:-------|
admin\_trad\_id |varchar(128) |Non |  |
admin\_trad\_fr |text |Non |  |
admin\_trad\_en |text |Non |  |
fk\_plugin\_id |varchar(64) |Non |

# s\_gabarit #
Commentaires sur la table: InnoDB free: 11264 kB

Champ |Type |Null |Défaut ||Commentaires|
|:----|:----|:-------|
gabarit\_id |smallint(5) |Non |  |
gabarit\_titre |varchar(128) |Non |  |
gabarit\_para\_crea |varchar(255) |Non |  |
gabarit\_para\_include |varchar(64) |Non |  |
gabarit\_full\_template |longtext |Non |  |
gabarit\_bdd\_deco |tinyint(1) |Non |0 |
gabarit\_classe |varchar(64) |Non |  |
gabarit\_classe\_param |varchar(255) |Non |  |
gabarit\_plugin |varchar(128) |Non |  |
gabarit\_index\_table |varchar(64) |Non |  |
gabarit\_index\_url |varchar(255) |Non |  |
fk\_default\_rubrique\_id |int(10) |Non ||0|

# s\_groupe #
Commentaires sur la table: InnoDB free: 11264 kB

Champ |Type |Null |Défaut ||Commentaires|
|:----|:----|:-------|
groupe\_id |int(10) |Non |  |
groupe\_nom |varchar(64) |Non |

# s\_image #
Commentaires sur la table: InnoDB free: 11264 kB

Champ |Type |Null |Défaut ||Commentaires|
|:----|:----|:-------|
image\_id |int(11) |Non |  |
image\_img |varchar(255) |Non |  |
image\_titre\_fr |varchar(255) |Non |  |
image\_legende\_fr |varchar(255) |Non |  |
image\_copyright |varchar(255) |Non |  |
image\_lien\_fr |varchar(255) |Oui |

# s\_langue #
Commentaires sur la table: InnoDB free: 11264 kB

Champ |Type |Null |Défaut ||Commentaires|
|:----|:----|:-------|
langue\_nom |varchar(64) |Non |  |
langue\_id |varchar(3) ||Non|
|  |
# s\_lock #
Commentaires sur la table: InnoDB free: 11264 kB

Champ |Type |Null |Défaut ||Commentaires|
|:----|:----|:-------|
fk\_admin\_id |smallint(5) |Non |0 |
lock\_table |varchar(64) |Non |  |
lock\_id |int(10) |Non |0 |
lock\_time |bigint(12) |Non ||0|


# s\_log\_action #
Commentaires sur la table: InnoDB free: 11264 kB

Champ |Type |Null |Défaut ||Commentaires|
|:----|:----|:-------|
log\_action\_id |int(10) |Non |  |
fk\_admin\_id |smallint(5) |Non |0 |
log\_action\_table |varchar(128) |Non |  |
log\_action\_fk\_id |varchar(255) |Non |0 |
log\_action\_action |varchar(128) |Non |  |
log\_action\_time |datetime |Non |0000-00-00 00:00:00 |

# s\_para\_type #
Commentaires sur la table: InnoDB free: 11264 kB

Champ |Type |Null |Défaut ||Commentaires|
|:----|:----|:-------|
para\_type\_id |smallint(5) |Non |  |
para\_type\_titre |varchar(128) |Non |  |
para\_type\_template |text |Non |  |
para\_type\_template\_popup |text |Non |  |
para\_type\_vignette |varchar(255) |Non |  |
para\_type\_use\_img |tinyint(1) |Non |0 |
para\_type\_use\_file |tinyint(1) |Non |0 |
para\_type\_use\_table |tinyint(1) |Non |0 |
para\_type\_use\_txt |tinyint(1) |Non |1 |
para\_type\_use\_link |tinyint(1) |Non |0 |

# s\_paragraphe #
Commentaires sur la table: InnoDB free: 11264 kB

Champ |Type |Null |Défaut ||Commentaires|
|:----|:----|:-------|
paragraphe\_id |bigint(20) |Non |  |
fk\_rubrique\_id |int(10) |Oui |NULL |
fk\_para\_type\_id |smallint(5) |Oui |NULL |
paragraphe\_ordre |tinyint(3) |Non |0 |
paragraphe\_titre\_fr |varchar(255) |Non |  |
paragraphe\_contenu\_fr |text |Non |  |
paragraphe\_contenu\_csv\_fr |text |Non |  |
paragraphe\_img\_1\_fr |varchar(255) |Non |  |
paragraphe\_img\_2\_fr |varchar(255) |Non |  |
paragraphe\_img\_1\_alt\_fr |varchar(80) |Non |  |
paragraphe\_img\_2\_alt\_fr |varchar(80) |Non |  |
paragraphe\_params\_fr |varchar(255) |Oui |NULL |
paragraphe\_file\_1\_fr |varchar(255) |Non |  |
paragraphe\_file\_1\_legend\_fr |varchar(255) |Non |  |
paragraphe\_link\_1\_fr |varchar(255) |Non |  |
paragraphe\_img\_1\_copyright |varchar(80) |Non |  |
paragraphe\_img\_2\_copyright |varchar(80) ||Non|
|  |
# s\_param #
Commentaires sur la table: InnoDB free: 11264 kB

Champ |Type |Null |Défaut ||Commentaires|
|:----|:----|:-------|
param\_id |varchar(64) |Non |  |
param\_valeur |text |Non |  |
param\_description |text |Non |  |
fk\_plugin\_id |varchar(64) |Non |  |

# s\_plugin #
Commentaires sur la table: InnoDB free: 11264 kB

Champ |Type |Null |Défaut ||Commentaires|
|:----|:----|:-------|
plugin\_nom |varchar(64) |Non |  |
plugin\_actif |tinyint(1) |Non |0 |
plugin\_ordre |smallint(5) |Non |0 |
plugin\_installe |tinyint(1) |Non |0 |

# s\_role #
Commentaires sur la table: InnoDB free: 11264 kB

Champ |Type |Null |Défaut ||Commentaires|
|:----|:----|:-------|
role\_id |int(10) |Non |  |
role\_nom |varchar(64) |Non |  |
s\_role\_table
Commentaires sur la table: InnoDB free: 11264 kB

Champ |Type |Null |Défaut ||Commentaires|
|:----|:----|:-------|
role\_table\_id |int(10) |Non |  |
role\_table\_table |varchar(64) |Non |  |
fk\_role\_id |int(10) |Non |0 |
role\_table\_type |enum('all', 'per\_user', 'specific') |Non |all |
role\_table\_specific |varchar(128) |Non |  |
role\_table\_view |tinyint(1) |Non |0 |
role\_table\_add |tinyint(1) |Non |0 |
role\_table\_edit |tinyint(1) |Non |0 |
role\_table\_delete |tinyint(1) |Non |0 |
role\_table\_actions |varchar(255) |Non |  |
role\_table\_champs |text |Non |

# s\_rubrique #
Commentaires sur la table: InnoDB free: 11264 kB

Champ |Type |Null |Défaut ||Commentaires|
|:----|:----|:-------|
rubrique\_id |int(10) |Non |  |
fk\_rubrique\_id |int(10) |Oui |NULL |
fk\_rubrique\_version\_id |int(10) |Oui |NULL |
rubrique\_ordre |tinyint(3) |Non |0 |
rubrique\_etat |enum('redaction', 'attente', 'en\_ligne') |Non |redaction |
rubrique\_gabarit\_param |varchar(255) |Non |  |
fk\_gabarit\_id |smallint(5) |Oui |NULL |
fk\_creator\_id |smallint(5) |Oui |NULL |
rubrique\_url\_fr |varchar(255) |Non |  |
rubrique\_titre\_fr |varchar(255) |Non |  |
rubrique\_sous\_titre\_fr |varchar(255) |Non |  |
rubrique\_texte\_fr |text |Non |  |
rubrique\_keywords\_fr |varchar(255) |Non |  |
rubrique\_desc\_fr |text |Non |  |
rubrique\_date\_crea |datetime |Non |0000-00-00 00:00:00 |
rubrique\_date\_modif |datetime |Non |0000-00-00 00:00:00 |
rubrique\_date\_publi |datetime |Non |0000-00-00 00:00:00 |
rubrique\_type |enum('page', 'link', 'folder', 'siteroot', 'menuroot') |Non |page |
rubrique\_link\_fr |varchar(255) |Non |  |
rubrique\_dyntitle |tinyint(1) |Non |0 |
rubrique\_dynvisibility |tinyint(1) |Non |0 |
rubrique\_template |varchar(255) |Non |  |
rubrique\_option |set('dynVisibility', 'dynTitle', 'dynSubRubs') |Non |  |

# s\_trad #
Commentaires sur la table: InnoDB free: 11264 kB

Champ |Type |Null |Défaut ||Commentaires|
|:----|:----|:-------|
trad\_id |varchar(64) |Non |  |
trad\_fr |text |Non |  |
fk\_plugin\_id |varchar(64) |Non |  |

# s\_traduction #
Commentaires sur la table: InnoDB free: 11264 kB

Champ |Type |Null |Défaut ||Commentaires|
|:----|:----|:-------|
fk\_table |varchar(32) |Non |  |
fk\_id |int(10) |Non |0 |
fk\_champ |varchar(64) |Non |  |
fk\_langue\_id |varchar(3) |Non |0 |
traduction\_texte |text |Non |  |

# s\_utilisateur #
Commentaires sur la table: InnoDB free: 11264 kB

Champ |Type |Null |Défaut ||Commentaires|
|:----|:----|:-------|
utilisateur\_id |int(10) |Non |  |
utilisateur\_civilite |enum('Mme', 'Mlle', 'Mr') |Non |Mme |
utilisateur\_nom |varchar(128) |Non |  |
utilisateur\_prenom |varchar(128) |Non |  |
utilisateur\_email |varchar(255) |Non |  |
utilisateur\_pwd |varchar(255) |Non |  |
utilisateur\_organisme |varchar(255) |Non |  |
utilisateur\_adresse |varchar(255) |Non |  |
utilisateur\_ville |varchar(64) |Non |  |
utilisateur\_cp |varchar(10) |Non |  |
utilisateur\_pays |varchar(64) |Non |  |
utilisateur\_tel |varchar(64) |Non |  |
utilisateur\_valide |tinyint(1) |Non |0 |