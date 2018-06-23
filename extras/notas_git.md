# NOTAS GIT

Traer cambios del servidor, baja desde remoto/rama_remota hasta local/"rama en la que este"
``` 
$ git pull <remote> <rama_remota> 
```

con ```> git log ``` muestro los commits que tengo

```
git branch <nombre_rama> <entry_point>
```

``` entry_point ``` basta con los primeros 10 dígitos
luego ```$ git checkuot <nombre_rama>```

o

``` 
git checkout -b <nombre_rama> <entry_point> 
```

Permite borrar una rama
```
$ git branch -D <nombre_rama>
```

Mandar a traer los commits que tiene la rama_x
```
$ git checkout -t <repositorio> <rama>
```

Saber el estado del respositorio
```
$ git status
```

Preparar un nuevo commit
```
# Agrega un archivo al commit
$ git add <path archivo modificad>
# Se realiza el commit con un mensaje descriptivo
$ git commit -m "MENSAJE"
```



mezcla la rama prueba en la rama en la que este actualmente (si quiero actualizar master primero me debo cambiar a esta)
```
git merge prueba
```

Subir cambios al servidor, sube a remoto/rama_remota desde local/"rama en la que este", si <rama_remota> no existe la crea

``` 
$ git push <remote> <rama_remota> 
```


Subir cambios al servidor, sube a remoto/rama_remota desde local/"rama en la que este", si <rama_remota> no existe la crea

``` 
$ git diff <archivo> 
```

