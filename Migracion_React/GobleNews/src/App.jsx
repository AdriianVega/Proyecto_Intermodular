import { useState } from 'react'

function App() {
  const [form, setForm] = useState({ usuario: '', password: '' });
  const [resultado, setResultado] = useState(null);

  const enviarFormulario = async (e) => {
    e.preventDefault(); // Evitar que la página se recargue
    
    const respuesta = await fetch('/api/validar.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(form) // Enviamos los datos como JSON
    });

    const data = await respuesta.json();
    setResultado(data);
  };

  return (
    <div style={{ padding: '20px' }}>
      <h1>Login - Goblenews</h1>
      <form onSubmit={enviarFormulario}>
        <input 
          type="text" 
          placeholder="Usuario" 
          onChange={(e) => setForm({...form, usuario: e.target.value})} 
        />
        <input 
          type="password" 
          placeholder="Contraseña" 
          onChange={(e) => setForm({...form, password: e.target.value})} 
        />
        <button type="submit">Validar en PHP</button>
      </form>

      {resultado && (
        <div style={{ marginTop: '20px', color: resultado.success ? 'green' : 'red' }}>
          <strong>{resultado.mensaje}</strong>
        </div>
      )}
    </div>
  )
}

export default App