#!/usr/bin/env python

"""
FCKeditor - The text editor for Internet - http://www.fckeditor.net
Copyright (C) 2003-2009 Frederico Caldeira Knabben

== BEGIN LICENSE ==

Licensed under the terms of any of the following licenses at your
choice:

- GNU General Public License Version 2 or later (the "GPL")
http://www.gnu.org/licenses/gpl.html

- GNU Lesser General Public License Version 2.1 or later (the "LGPL")
http://www.gnu.org/licenses/lgpl.html

- Mozilla Public License Version 1.1 or later (the "MPL")
http://www.mozilla.org/MPL/MPL-1.1.html

== END LICENSE ==

Base Connector for Python (CGI and WSGI).

See config.py for configuration settings

"""
import cgi, os

from fckutil import *
from fckcommands import * 	# default command's implementation
from fckoutput import * 	# base http, xml and html output mixins
import config as Config

class FCKeditorConnectorBase( object ):
	"The base connector class. Subclass it to extend functionality (see Zope example)"

	def __init__(self, environ=None):
		"Constructor: Here you should parse request fields, initialize variables, etc."
		self.request = FCKeditorRequest(environ) # Parse request
		self.headers = []						# Clean Headers
		if environ:
			self.environ = environ
		else:
			self.environ = os.environ

	# local functions

	def setHeader(self, key, value):
		self.headers.append ((key, value))
		return

class FCKeditorRequest(object):
	"A wrapper around the request object"
	def __init__(self, environ):
		if environ: # WSGI
			self.request = cgi.FieldStorage(fp=environ['wsgi.input'],
							environ=environ,
							keep_blank_values=1)
			self.environ = environ
		else: # plain old cgi
			self.environ = os.environ
			self.request = cgi.FieldStorage()
		if 'REQUEST_METHOD' in self.environ and 'QUERY_STRING' in self.environ:
			if self.environ['REQUEST_METHOD'].upper()=='POST':
				# we are in a POST, but GET query_string exists
				# cgi parses by default POST data, so parse GET QUERY_STRING too
				self.get_request = cgi.FieldStorage(fp=None,
							environ={
							'REQUEST_METHOD':'GET',
							'QUERY_STRING':self.environ['QUERY_STRING'],
							},
							)
		else:
			self.get_request={}

	def has_key(self, key):
		return self.request.has_key(key) or self.get_request.has_key(key)

	def get(self, key, default=None):
		if key in self.request.keys():
			field = self.request[key]
		elif key in self.get_request.keys():
			field = self.get_request[key]
		else:
			return default
		if hasattr(field,"filename") and field.filename: #file upload, do not convert return value
			return field
		else:
			return field.value
