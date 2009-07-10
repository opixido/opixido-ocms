<?php

# ***** BEGIN LICENSE BLOCK *****
# Copyright (c) 2004 Olivier Meunier. All rights reserved.
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
#
# ***** END LICENSE BLOCK *****

class crypto
{
	var $cipher;
	var $mode;
	var $key;

	function crypto($cipher,$mode,$key)
	{
		$this->_setCipher($cipher);
		$this->_setMode($mode);
		$this->_setKey($key);
	}

	function createIV()
	{
		if (($td = $this->_openModule()) !== false)
		{
			if (($iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td),MCRYPT_RAND)) === false) {
				trigger_error('Impossible de créer le vecteur d\initialisation',E_USER_ERROR);
			}

			$this->_closeModule($td);
			return $iv;
		}
		else
		{
			return false;
		}
	}

	function encrypt($str)
	{
		if (($iv = $this->createIV()) === false) {
			return false;
		}

		if (($td = $this->_openModule()) !== false)
		{
			mcrypt_generic_init($td,$this->key,$iv);

			$res = mcrypt_generic($td,$str);

			mcrypt_generic_deinit($td);
			$this->_closeModule($td);

			# On retourne une chaine en base 64 contenant :
			# taille de la chaine en binaire sur 32 caractère
			# IV
			# chaine encryptée
			$bsize = sprintf('%032b',strlen($str));
			return base64_encode($bsize.$iv.$res);
		}
		else
		{
			return false;
		}
	}

	function decrypt($str)
	{
		if (($td = $this->_openModule()) !== false)
		{
			$ivsize = mcrypt_enc_get_iv_size($td);

			$str = base64_decode($str);

			# On récupère la taille de la chaîne
			$bsize = substr($str,0,32);
			$str_size = bindec($bsize);
			$str = substr($str,32);

			# On récupère l'IV de la chaîne
			$iv = substr($str,0,$ivsize);
			$str = substr($str,$ivsize);
			
			@mcrypt_generic_init($td,$this->key,$iv);

			$res = @mdecrypt_generic ($td,$str);

			mcrypt_generic_deinit($td);
			$this->_closeModule($td);

			# On corrige la taille de la chaîne.
			$res = substr($res,0,$str_size);

			return $res;
		}
		else
		{
			return false;
		}
	}

	function getModes()
	{
		$res = '';
		foreach (mcrypt_list_modes() as $v) {
			$res .= $v."\n";
		}
		return $res;
	}

	function getCiphers()
	{
		$res = '';
		foreach (mcrypt_list_algorithms() as $v) {
			$res .= $v."\n";
		}
		return $res;
	}


	/* --------------------------------------------------------
	Méthodes privées
	-------------------------------------------------------- */


	function _setCipher($cipher)
	{
		if (in_array($cipher,mcrypt_list_algorithms())) {
			$this->cipher = $cipher;
		} else {
			trigger_error('Crypto : impossible d\'initialiser l\'algorithme '.$cipher,E_USER_ERROR);
			return false;
		}
	}

	function _setMode($mode)
	{
		
		if (in_array($mode,mcrypt_list_modes())) {
			$this->mode = $mode;
		} else {
			trigger_error('Crypto : impossible d\'initialiser le mode '.$mode,E_USER_ERROR);
			return false;
		}
	}

	function _setKey($key)
	{
		$keysize = mcrypt_get_key_size ($this->cipher, $this->mode);

		if ((strlen($key) < 32) && ($keysize >= 32)) {
			$this->key = md5($key);
		} elseif ((strlen($key) > $keysize) && ($keysize == 32)) {
			$this->key = md5($key);
		} else {
			$this->key = substr($key, 0, $keysize);
		}
	}

	function _openModule()
	{
		if (($td = mcrypt_module_open($this->cipher,'',$this->mode,'')) !== false) {
			return $td;
		} else {
			trigger_error('Crypto : impossible de d\'initialiser le module',E_USER_ERROR);
			return false;
		}
	}

	function _closeModule($td)
	{
		@mcrypt_module_close($td);
	}
}

?>