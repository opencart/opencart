/*
 * CKFinder
 * ========
 * http://ckfinder.com
 * Copyright (C) 2007-2009, CKSource - Frederico Knabben. All rights reserved.
 *
 * The software, this file and its contents are subject to the CKFinder
 * License. Please read the license.txt file before using, installing, copying,
 * modifying or distribute this file or part of its contents. The contents of
 * this file is part of the Source Code of CKFinder.
 *
 * ---
 * Polish language file.
 */

var CKFLang =
{

Dir : 'ltr',
HelpLang : 'pl',
LangCode : 'pl',

// Date Format
//		d    : Day
//		dd   : Day (padding zero)
//		m    : Month
//		mm   : Month (padding zero)
//		yy   : Year (two digits)
//		yyyy : Year (four digits)
//		h    : Hour (12 hour clock)
//		hh   : Hour (12 hour clock, padding zero)
//		H    : Hour (24 hour clock)
//		HH   : Hour (24 hour clock, padding zero)
//		M    : Minute
//		MM   : Minute (padding zero)
//		a    : Firt char of AM/PM
//		aa   : AM/PM
DateTime : 'yyyy-mm-dd HH:MM',
DateAmPm : ['AM','PM'],

// Folders
FoldersTitle	: 'Katalogi',
FolderLoading	: 'Ładowanie...',
FolderNew		: 'Podaj nazwę nowego katalogu: ',
FolderRename	: 'Podaj nową nazwę katalogu: ',
FolderDelete	: 'Czy na pewno chcesz usunąć katalog "%1"?',
FolderRenaming	: ' (Zmieniam nazwę...)',
FolderDeleting	: ' (Kasowanie...)',

// Files
FileRename		: 'Podaj nową nazwę pliku: ',
FileRenameExt	: 'Czy na pewno chcesz zmienić rozszerzenie pliku? Może to spowodować problemy z otwieraniem pliku przez innych użytkowników',
FileRenaming	: 'Zmieniam nazwę...',
FileDelete		: 'Czy na pewno chcesz usunąć plik "%1"?',

// Toolbar Buttons (some used elsewhere)
Upload		: 'Wyślij',
UploadTip	: 'Wyślij plik',
Refresh		: 'Odśwież',
Settings	: 'Ustawienia',
Help		: 'Pomoc',
HelpTip		: 'Wskazówka',

// Context Menus
Select		: 'Wybierz',
SelectThumbnail		: 'Wybierz miniaturkę',
View		: 'Zobacz',
Download	: 'Pobierz',

NewSubFolder	: 'Nowy podkatalog',
Rename			: 'Zmień nazwę',
Delete			: 'Usuń',

// Generic
OkBtn		: 'OK',
CancelBtn	: 'Anuluj',
CloseBtn	: 'Zamknij',

// Upload Panel
UploadTitle			: 'Wyślij plik',
UploadSelectLbl		: 'Wybierz plik',
UploadProgressLbl	: '(Trwa wysyłanie pliku, proszę czekać...)',
UploadBtn			: 'Wyślij wybrany plik',

UploadNoFileMsg		: 'Wybierz plik ze swojego komputera',

// Settings Panel
SetTitle		: 'Ustawienia',
SetView			: 'Widok:',
SetViewThumb	: 'Miniaturki',
SetViewList		: 'Lista',
SetDisplay		: 'Wyświetlanie:',
SetDisplayName	: 'Nazwa pliku',
SetDisplayDate	: 'Data',
SetDisplaySize	: 'Rozmiar pliku',
SetSort			: 'Sortowanie:',
SetSortName		: 'wg nazwy pliku',
SetSortDate		: 'wg daty',
SetSortSize		: 'wg rozmiaru',

// Status Bar
FilesCountEmpty : '<Pusty katalog>',
FilesCountOne	: '1 plik',
FilesCountMany	: 'Ilość plików: %1',

// Size and Speed
Kb				: '%1 kB',
KbPerSecond		: '%1 kB/s',

// Connector Error Messages.
ErrorUnknown : 'Wykonanie operacji zakończyło się niepowodzeniem. (Błąd %1)',
Errors :
{
 10 : 'Nieprawidłowe polecenie (command).',
 11 : 'Brak wymaganego parametru: źródło danych (type).',
 12 : 'Nieprawidłowe źródło danych (type).',
102 : 'Nieprawidłowa nazwa pliku lub katalogu.',
103 : 'Wykonanie operacji nie jest możliwe: brak autoryzacji.',
104 : 'Wykonanie operacji nie powiodło się z powodu niewystarczających uprawnień do systemu plików.',
105 : 'Nieprawidłowe rozszerzenie.',
109 : 'Nieprawiłowe polecenie.',
110 : 'Niezidentyfikowany błąd.',
115 : 'Plik lub katalog o podanej nazwie już istnieje.',
116 : 'Nie znaleziono ktalogu. Odśwież panel i spróbuj ponownie.',
117 : 'Nie znaleziono pliku. Odśwież listę plików i spróbuj ponownie.',
201 : 'Plik o podanej nazwie już istnieje. Nazwa przesłanego pliku została zmieniona na "%1"',
202 : 'Nieprawidłowy plik.',
203 : 'Nieprawidłowy plik. Plik przekroczył dozwolony rozmiar.',
204 : 'Przesłany plik jest uszkodzony.',
205 : 'Brak folderu tymczasowego na serwerze do przesyłania plików.',
206 : 'Przesyłanie pliku zakończyło się niepowodzeniem z powodów bezpieczeństwa. Plik zawiera dane przypominające HTML.',
500 : 'Menedżer plików jest wyłączony z powodów bezpieczeństwa. Skontaktuj się z administratorem oraz sprawdź plik konfiguracyjny CKFindera.',
501 : 'Tworzenie miniaturek jest wyłączone.'
},

// Other Error Messages.
ErrorMsg :
{
FileEmpty		: 'Nazwa pliku nie może być pusta',
FolderEmpty		: 'Nazwa katalogu nie może być pusta',

FileInvChar		: 'Nazwa pliku nie może zawierać żadnego z podanych znaków: \n\\ / : * ? " < > |',
FolderInvChar	: 'Nazwa katalogu nie może zawierać żadnego z podanych znaków: \n\\ / : * ? " < > |',

PopupBlockView	: 'Otwarcie pliku w nowym oknie nie powiodło się. Proszę zmienić konfigurację przeglądarki i wyłączyć wszelkie blokady okienek popup dla tej strony.'
}

} ;
