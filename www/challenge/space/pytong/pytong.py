#!/usr/bin/python

import sys
import os

# Your main objective is to return 0
def main(argv):
	if not os.path.exists(argv):
		print('sorry file does not exists: '+argv)
		return 1

	print('opening '+argv)
	gizmore = open(argv)
	jjk = ''.join(gizmore.readlines())
	gizmore.close()
	print('closed')

	if not os.path.exists(argv):
		print('You are l33t')
		return 0
	else:
		spaceone = open(argv)
		kwisatz = ''.join(spaceone.readlines())
		spaceone.close()
		if jjk != kwisatz:
			print('You are a winner')
			return 0
	return 1

def sanitize_arg(value):
	# no symlinks, etc. please
	pattern = ['proc', '..', 'tmp', 'random', 'full', 'zero', 'null']
	for ipattern in pattern:
		while ipattern in value:
			value = value.replace(ipattern, '')
	for ipattern in pattern:
		if ipattern in value:
			return 'nononono: hacking is not allowed'
	
	return value

if __name__ == "__main__":
	rc = 1
	try:
		if len(sys.argv[1]) > 1:
			rc = main(sanitize_arg(sys.argv[1]))
	except:
		print('an exception occured: maybe no file permissions!')
		rc = 1
	sys.exit(rc)
