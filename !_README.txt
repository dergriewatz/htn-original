

    +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    ++                                                                     ++
    ++     HH     HH  TTTTTTTTTTTTT  NNN      NN                           ++
    ++     HH     HH       TT        NNNNN    NN                           ++
    ++     HH     HH       TT        NN  NN   NN               2222222     ++
    ++     HH HHH HH       TT        NN   NN  NN   vv      vv       22     ++
    ++     HH     HH       TT        NN    NN NN    vv    vv    222222     ++
    ++     HH     HH       TT        NN     NNNN     vv  vv     22         ++
    ++     HH     HH       TT        NN      NNN      vvvv      222222     ++
    ++                                                                     ++
    ++     H A C K T H E N E T  V E R S I O N  2  [ Q U E L L C O D E ]    ++
    ++                                                                     ++
    +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


    >>> Bei Fragen besuchen Sie das Quelltext-Forum auf www.hackthenet.org <<<
    >>>        Anfragen per Email oder PM werden nicht beantwortet!        <<<

    
    Version htn2src.2.0-RC5
    
    Systemanforderungen:
      PHP 4: mindestens PHP 4.2.0 (nicht lauff�hig unter PHP 5)
      MySQL 4.0.x
      Apache-Webserver empfohlen
      
      Unter Linux:
        chmod -R 0777 data
       oder
        chown -R <apache-user> data
      
    History:
      htn2src.2.0-RC1 (2.9.04) - Erster Release Candidate
      htn2src.2.0-RC2 (3.9.04) - Zweiter RC, einige �nderungen im Detail gegen�ber dem ersten.
      htn2src.2.0-RC3 (3.9.04) - Dritter RC, weitere Bugfixes.
      htn2src.2.0-RC4 (4.9.04) - Einige Bugfixes:
            cboard.htn - "s"-User gefixt
            cluster.htn - diverse \n's entfernt
            game.htn - Anzeige des Hijack-Levels in der PC-�bersicht hinzugef�gt (danke an Eraser)
            mail.htn - Bug mit \s gefixt
            user.htn - Passwort �ndern-Funktion f�r Admins gefixt
            Mini-"Doku" hinzugef�gt
      htn2src.2.0-RC5 (9.9.04) - Einige Bugfixes:
            .htaccess
            login.htn - kritischer Bug gefixt!
            weitere Dateien - kleinere Darstellungsfehler entfernt
            
    
1. Lizenz
   Dieser Quellcode steht unter einer Creative Commons License:
   http://creativecommons.org/licenses/by-nc-sa/2.0/de/
   (Namensnennung-NichtKommerziell-Weitergabe unter gleichen Bedingungen 2.0 Deutschland)
   zusammengefasst auch in der Datei license_by-nc-sa_2.0_de.txt ...
   Der vollst�ndige Text kann hier abgerufen werden: http://creativecommons.org/licenses/by-nc-sa/2.0/de/legalcode
   
   Au�erdem sind Sie nicht berechtigt, den Hinweis unter "Team" oder den Link auf diese Seite zu entfernen.
   
   Die Icons im Crystal-Stylesheet stehen unter LGPL. Details siehe lizenz.txt und
   lgpl.txt im static-Verzeichnis.

2. Haftungsausschluss
   Die Autoren dieses Quelltexts k�nnen nichts garantieren und keinerlei Verantwortung
   f�r jegliche Fehler oder Sch�den die durch diesen Quelltext verursacht werden, �bernehmen.
   Wir k�nnen f�r nichts, was Ihnen, Ihrem Computer, Ihrer Katze, Ihrem Sexleben oder irgendetwas
   anderem durch die Benutzung oder Nicht-Benutzung des Quelltextes passieren kann, Verantwortung
   �bernehmen. Sie benutzen den Quelltext zu 100% zu ihrem eigenen Risiko!
   Es besteht ebenfalls kein Anspruch auf Support.
   
3. Installation
   F�hren Sie die SQL-Befehle in der Datei DATABASE.DUMP.SQL aus (z.B. mit phpMyAdmin).
   Dadurch wird eine Datenbank htn_server1 angelegt.
   Jetzt k�nnen sie sich schon mit folgenden Benutzern einloggen:
    Administrator
    Administrator2
    TestUser
   Die Passw�rter f�r die Accounts sind jeweils ein leeres Passwortfeld. Die ersten beiden
   Accounts sind im "god-mode". Sie k�nnen also nicht angegriffen werden. Au�erdem stehen
   von diesen Accounts aus Administrator-Funktionen zur Verf�gung, man kann also die Daten
   von Spielern, PCs und Clustern einsehen und �ndern.
   Weitere Accounts k�nnen sie �ber die Registrieren-Funktion hinzuf�gen!

4. Modifikationen des Quellcodes
   Wenn Sie den Code umgeschrieben oder erweitert haben, k�nnen sie ihn an
   htn2code@hackthenet.org schicken (als komprimiertes Archiv, z.B. ZIP, RAR oder GZip)
   wenn sie m�chten, dass ihre modifizierte Version auf www.hackthenet.org allen
   interessierten zum Download bereitgestellt wird.

5. Wie man sich am besten zurechtfindet.
   Man nehme eine installiertes HackTheNet und klicke ein bisschen auf den Links rum.
   In der URL in der Adresszeile findet man einen Parameter, der page, a, action, m oder
   mode hei�t.
   Dann �ffne man die entspr. Datei und suche dort nach Wert dieses Parameters. Dann d�rfte
   man relativ schnell f�ndig werden!
   
X. Enjoy
   Trotz des schlechten Programmierstils w�nschen wir allen viel Spa� mit diesem Code!
   Das HackTheNet-Team
   
   
   
   
