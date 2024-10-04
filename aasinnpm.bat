@echo on
set destination=C:\laragon\www\ageneralcma\ageneralcma.zip
del %destination%
"C:\Program Files\7-Zip\7z.exe" a -r -x!"C:\laragon\www\ageneralcma\vendor" -x!"C:\laragon\www\ageneralcma\storage" -x!"C:\laragon\www\ageneralcma\node_modules" -x!"C:\laragon\www\ageneralcma\public\hot.*"  %destination% "C:\laragon\www\ageneralcma\app" "C:\laragon\www\ageneralcma\resources" "C:\laragon\www\ageneralcma\routes" "C:\laragon\www\ageneralcma\database" "C:\laragon\www\ageneralcma\lang"
