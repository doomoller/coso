# coso
 Simple punto de inicio para proyectos MVC en PHP.
 
 Tiene toda la estructura de carpeas listas para empezar un proyecto sencillo Web basado en PHP y el modelo MVC. 
 Tiene una clase base para las controladoras (AppBase) para la lógica común de la app.
 
 public/index.php es el punto de inicio. Asigne los routers y Main.php se encarga de hacer todo el ruteo a las controladoras/acciones.
 La clase base de las controladoras AppBase proporciona acceso fácil a la base de datos (PDO) por medio de DB, a su vez da acceso a Main para obtener las Requests.
 Asi, desde las controladoras puede acceder a los parametros request:
  
   $req = $this->main->getRequest();
   if($req->getQueryParam("id") != null){}

Desde la controladora tiene acceso a la base de datos asi:

  $this->db()->  // apunta a la intancia PDO
 
  eje: 
 
  $result = $this->db()->select(new Select('usuarios', "*", null, "usuarios.id = :id and usuarios.activo = 1"), array("id" => $id));
 
 Para configurar el acceso a la base de datos:
 
  sitio/config/db-*.ini
  
La salida se hace con 3 Renders, RenderPHP, RenderHTML, y RenderJSON. 

Api / JSON

Para consultas ajax se puede pasar al inicio del URI la opción "/api/". Con estó, siempre se devolverá un JSON en lugar de HTML, tampoco se procesará la view o el partial, solo tiene la salida json de la controladora. Eje:

 /usuarios/login       // devuelve HTML del formulario procesado en la view con PHP
 
 /api/usuarios/login   // devuelve el array pasado en $this->view como un JSON directamente de la controladora
