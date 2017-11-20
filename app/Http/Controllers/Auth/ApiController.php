<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Usuario;
use App\Models\Denuncia;

//Crear usuario-----
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
//------------------

class ApiController extends Controller
{
    
    //Acción que permite iniciar sesión
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        
        if (Auth::once($credentials)) 
        {
         $user = Auth::user();
         
         return $user;
        }
        return response()->json(['error' => 'Usuario y/o clave inválido'], 401); 
    }
    
    //Acción que permite registrar usuario
    public function register(Request $request)
    {
        try
        {
            if(!$request->has(['username','password','email','fullname']))
            {
                throw new \Exception('Se esperaba campos mandatorios');
            }
            
            $producto = new Usuario();
            $producto->username = $request->get('username');
            
    		$hash_pass = $request->get('password');
    		$producto->password = password_hash($hash_pass, PASSWORD_DEFAULT);
    		
    		$producto->email = $request->get('email');
    		$producto->fullname = $request->get('fullname');
    		
    // 		if($request->hasFile('imagen') && $request->file('imagen')->isValid())
    // 		{
    //     		$imagen = $request->file('imagen');
    //     		$filename = $request->file('imagen')->getClientOriginalName();
        		
    //     		Storage::disk('images')->put($filename,  File::get($imagen));
        		
    //     		$producto->imagen = $filename;
    // 		}
    		
    		$producto->save();
    	    
    	    return response()->json(['type' => 'success', 'message' => 'Registro completo'], 200);
    	    
        }catch(\Exception $e)
        {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    //Muestra todas las denuncias hechas por todos los usuarios
    public function index()
    {
        $denuncias = Denuncia::select('denuncias.id', 'usuarios.username', 'denuncias.titulo', 'denuncias.descripcion', 'denuncias.imagen', 'denuncias.ubicacion')
                            ->join('usuarios', 'denuncias.usuario_id', '=', 'usuarios.id')
                            ->orderBy("denuncias.titulo")
                            ->get();
                
	    return $denuncias;
    }
    
    //Muestra solo las denuncias del usuario
    public function denuncias_usuario($usuario_id)
    {
        $denuncias = Denuncia::where('usuario_id', $usuario_id)->orderby("titulo")->get();
	    
	    return $denuncias;
    }
    
    //Muestra el detalle de la denuncia
    public function show($id){
        try
        {
            $denuncia = Denuncia::find($id);
            
            if($denuncia == null)
                throw new \Exception('Registro no encontrado');
    		
            return $denuncia;
    	    
        }catch(\Exception $e)
        {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    
    //Almacena un denuncia
    public function store(Request $request)
    {
        try
        {
            if(!$request->has('titulo') || !$request->has('descripcion'))
            {
                throw new \Exception('Se esperaba campos mandatorios');
            }
            
            $denuncia = new Denuncia();
            
            $denuncia->usuario_id = $request->get('usuario_id');
            $denuncia->titulo = $request->get('titulo');
    		$denuncia->descripcion = $request->get('descripcion');
    		$denuncia->ubicacion = $request->get('ubicacion');
    		
    		if($request->hasFile('imagen') && $request->file('imagen')->isValid())
    		{
        		$imagen = $request->file('imagen');
        		$filename = $request->file('imagen')->getClientOriginalName();
        		
        		//Aquí, con la etiqueta 'denuncias', hacemos referencia al Filesystem Disk dónde queremos almacenar las imágenes u otro tipo de dato
        		Storage::disk('denuncias')->put($filename,  File::get($imagen));
        		
        		$denuncia->imagen = $filename;
    		}
    		
    		$denuncia->save();
    	    
    	    return response()->json(['type' => 'success', 'message' => 'Registro completo'], 200);
    	    
        }catch(\Exception $e)
        {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
