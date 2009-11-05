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
 * Portuguese (Brazilian) language file.
 */

var CKFLang =
{

Dir : 'ltr',
HelpLang : 'en',
LangCode : 'pt-br',

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
DateTime : 'dd/mm/yyyy HH:MM',
DateAmPm : ['AM','PM'],

// Folders
FoldersTitle	: 'Pastas',
FolderLoading	: 'Carregando...',
FolderNew		: 'Favor informar o nome da nova pasta: ',
FolderRename	: 'Favor informar o nome da nova pasta: ',
FolderDelete	: 'Você tem certeza que deseja apagar a pasta "%1"?',
FolderRenaming	: ' (Renomeando...)',
FolderDeleting	: ' (Apagando...)',

// Files
FileRename		: 'Favor informar o nome do novo arquivo: ',
FileRenameExt	: 'Você tem certeza que deseja alterar a extensão do arquivo? O arquivo pode ser danificado',
FileRenaming	: 'Renomeando...',
FileDelete		: 'Você tem certeza que deseja apagar o arquivo "%1"?',

// Toolbar Buttons (some used elsewhere)
Upload		: 'Enviar arquivo',
UploadTip	: 'Enviar novo arquivo',
Refresh		: 'Atualizar',
Settings	: 'Configurações',
Help		: 'Ajuda',
HelpTip		: 'Ajuda',

// Context Menus
Select		: 'Selecionar',
SelectThumbnail : 'Selecionar miniatura',
View		: 'Visualizar',
Download	: 'Download',

NewSubFolder	: 'Nova sub-pasta',
Rename			: 'Renomear',
Delete			: 'Apagar',

// Generic
OkBtn		: 'OK',
CancelBtn	: 'Cancelar',
CloseBtn	: 'Fechar',

// Upload Panel
UploadTitle			: 'Enviar novo arquivo',
UploadSelectLbl		: 'Selecione o arquivo para enviar',
UploadProgressLbl	: '(Enviado arquivo, favor aguardar...)',
UploadBtn			: 'Enviar arquivo selecionado',

UploadNoFileMsg		: 'Favor selecionar o arquivo no seu computador',

// Settings Panel
SetTitle		: 'Configurações',
SetView			: 'Visualizar:',
SetViewThumb	: 'Miniaturas',
SetViewList		: 'Lista',
SetDisplay		: 'Exibir:',
SetDisplayName	: 'Arquivo',
SetDisplayDate	: 'Data',
SetDisplaySize	: 'Tamanho',
SetSort			: 'Ordenar:',
SetSortName		: 'por Nome do arquivo',
SetSortDate		: 'por Data',
SetSortSize		: 'por Tamanho',

// Status Bar
FilesCountEmpty : '<Pasta vazia>',
FilesCountOne	: '1 arquivo',
FilesCountMany	: '%1 arquivos',

// Size and Speed
Kb				: '%1 kB',
KbPerSecond		: '%1 kB/s',

// Connector Error Messages.
ErrorUnknown : 'Não foi possível completer o seu pedido. (Erro %1)',
Errors :
{
 10 : 'Comando inválido.',
 11 : 'O tipo de recurso não foi especificado na solicitação.',
 12 : 'O recurso solicitado não é válido.',
102 : 'Nome do arquivo ou pasta inválido.',
103 : 'Não foi possível completar a solicitação por restrições de acesso.',
104 : 'Não foi possível completar a solicitação por restrições de acesso do sistema de arquivos.',
105 : 'Extensão de arquivo inválida.',
109 : 'Solicitação inválida.',
110 : 'Erro desconhecido.',
115 : 'Uma arquivo ou pasta já existe com esse nome.',
116 : 'Pasta não encontrada. Atualize e tente novamente.',
117 : 'Arquivo não encontrado. Atualize a lista de arquivos e tente novamente.',
201 : 'Um arquivo com o mesmo nome já está disponível. O arquivo enviado foi renomeado para "%1"',
202 : 'Arquivo inválido',
203 : 'Arquivo inválido. O tamanho é muito grande.',
204 : 'O arquivo enviado está corrompido.',
205 : 'Nenhuma pasta temporária para envio está disponível no servidor.',
206 : 'Transmissão cancelada por razões de segurança. O arquivo contem dados HTML.',
500 : 'A navegação de arquivos está desativada por razões de segurança. Contacte o administrador do sistema.',
501 : 'O suporte a miniaturas está desabilitado.'
},

// Other Error Messages.
ErrorMsg :
{
FileEmpty		: 'O nome do arquivo não pode ser vazio',
FolderEmpty		: 'O nome da pasta não pode ser vazio',

FileInvChar		: 'O nome do arquivo não pode conter nenhum desses caracteres: \n\\ / : * ? " < > |',
FolderInvChar	: 'O nome da pasta não pode conter nenhum desses caracteres: \n\\ / : * ? " < > |',

PopupBlockView	: 'Não foi possível abrir o arquivo em outra janela. Configure seu navegador e desabilite o bloqueio a popups para esse site.'
}

} ;
