#!/bin/bash
# Clear Export Directory
# (c)2010 Steffen Eichhorn mail@indubio.org
# This file is licensed under the GPL v2
#
## variables
do_nothing=""        # debug option true=dont delete anything
older="1"            # delete files older than...
delfilecmd="rm -f"   # command to delete file
deldircmd="rm -rf"   # command to delete directory

## set html export dir
script=$(readlink -f $0)
scriptpath=$(dirname $script)
system_dir=${scriptpath%/*}   # one up
bes_dir=${system_dir%/*}      # again one up
dummy="/html/export"
export_dir=$bes_dir$dummy

## debug condition
if [ "$do_nothing" == "true" ]
then
  delfilecmd="echo f"
  deldircmd="echo d"
fi

## write lockfile to protect exportdir
echo . > $export_dir/lock

## find and delete files
find $export_dir -type f \( ! -iname ".gitignore" -and ! -iname "info.txt" \) -mmin +$older -exec $delfilecmd {} \;

## find and delete directorys
find $export_dir -depth -type d -mmin +$older -exec $deldircmd {} \;

## delete lockfile
rm -f $export_dir/lock

