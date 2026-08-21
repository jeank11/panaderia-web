# Ranking online — configuración

La versión del juego incluye ranking local de respaldo y ranking online con Supabase.

## 1. Crear proyecto
Crea un proyecto gratuito en Supabase.

## 2. Habilitar jugadores anónimos
En Supabase, habilita **Anonymous Sign-Ins** en Authentication.

Los jugadores no tendrán que crear una cuenta ni ingresar email: Supabase crea una identidad anónima persistente en ese navegador. Si se borran los datos del navegador o se cambia de dispositivo, esa identidad anónima no se puede recuperar automáticamente.

## 3. Crear la tabla
Abre **SQL Editor** y ejecuta el archivo `supabase-setup.sql` incluido en este paquete.

## 4. Exponer la tabla
En la sección **Data API**, verifica que `public.player_rankings` esté expuesta.

## 5. Configurar el juego
Abre:

`js/supabase-config.js`

Completa:

```js
const SUPABASE_URL = "https://TU-PROYECTO.supabase.co";
const SUPABASE_PUBLISHABLE_KEY = "TU-PUBLISHABLE-KEY";
```

Usa la **Publishable Key** del proyecto. Nunca coloques una `service_role` o secret key en el navegador.

## 6. Probar
Abre el juego y completa un nivel. El resultado se guarda localmente y también se intenta sincronizar con Supabase.

Abre **🏆 Ranking** desde otro navegador/dispositivo configurado contra el mismo proyecto para verificar que aparece el jugador.

### Importante
Este ranking autentica al jugador anónimamente y usa RLS para que cada usuario solo pueda modificar su propia fila. Eso evita que un navegador anónimo escriba directamente la puntuación de otro jugador. Sin embargo, una partida que vive enteramente en el navegador todavía puede ser manipulada por alguien que modifique el JavaScript y envíe una puntuación falsa. Para un ranking competitivo/anti-trampa serio, más adelante conviene validar la puntuación en un backend.
