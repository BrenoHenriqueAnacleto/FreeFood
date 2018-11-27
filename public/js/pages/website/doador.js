$(document).ready(function(){
   
    $('#cadastro').validate({
		rules:{
			nome:{
				required:true
			},
			email:{
				required:true
			},
			senha:{
				required:true
			},
			confirmasenha:{
				equalTo:"#senha",
				required:true
			},
			cep:{
				required:true,
				pesquisaCep:true
			},
			cpf:{
				required:true
			},
                        rg:{
				required:true
			},
                        tipo_pessoa:{
				required:true
			},
                        rua:{
				required:true
			},
                        bairro:{
				required:true
			},
                        cidade:{
				required:true
			},
                        numero:{
				required:true
			},
                        cnpj:{
				required:true
			},
                        nome_fantasia:{
				required:true
			},
                        ie:{
				required:true
			},

		},
		 messages: {
		 	nome: 'Campo Obrigatório',
		 	email: 'Campo Obrigatório',
		 	senha:'Campo Obrigatório',
		 	confirmasenha: 'Campo Obrigatório',
		 	cep: 'Campo Obrigatório',
		 	rg: 'Campo Obrigatório',
                        cpf: 'Campo Obrigatório',
                        cnpj: 'Campo Obrigatório',
                        ie: 'Campo Obrigatório',
                        nome_fantasia: 'Campo Obrigatório',
                        tipo_pessoa: 'Campo Obrigatório',
                        bairro: 'Campo Obrigatório',
                        cidade: 'Campo Obrigatório',
                        rua: 'Campo Obrigatório',
                        numero: 'Campo Obrigatório',
		 }
	});
        
        $('.error').css("color","red");
});