package lib.com.camera {
	
	import flash.display.Bitmap;
	import flash.display.BitmapData;
	import flash.display.SimpleButton;
	import flash.display.Stage;
	import flash.display.MovieClip;
	import flash.geom.Matrix;
	import flash.media.Camera;
	import flash.media.Video;
	import flash.events.Event;
	
	public class WebCam extends MovieClip {
		
		private var camera:Camera;
		private var video:Video;
	  
		private var stageP:Stage;
		private var mc:MovieClip;
		
	    // figura preview na tela
		private var bmdPicturePreview:Bitmap;
		private var bmdDataPicturePreview:BitmapData;
		
		// figura que será armazenada em disco
		private var bmdDataPicture:BitmapData;
		
		private var started:Boolean;
		
		private var btnCapturar:SimpleButton;
		
		public function WebCam(stageP:Stage) { this.stageP = stageP; }
	  
		public function start():Boolean {
			camera = Camera.getCamera();

			if (camera) {
				// cria o video
				video = new Video();
				
				// setando as dimensões
				video.width  = this.stageP.stageWidth-280;
			    video.height = this.stageP.stageHeight-92; 
				
				// setando as coordenadas
				video.x = 15;
				video.y = 15;

				// adicionando a camera
				video.attachCamera(camera);
				
				// adicionando o video ao container (movieclip empty no stage)
				this.stageP.addChild(video);
							
				started = true;
				return true;
			} else {
				trace("Não existe camera conectada");
				return false;
			}
		}
		
		public function getObjectVideo():Video { return this.video;	}
		
		public function getbmdDataPicture():BitmapData { return this.bmdDataPicture; }
		
		public function setEventReloadCamera() {
			function reloadCamera() {
				this.start();
			}
			
			this.addEventListener(Event.ENTER_FRAME,reloadCamera);
		}
		
		public function getPicture():Bitmap {
			// setando parâmetro matrix
			var matrix:Matrix = new Matrix();
			matrix.scale(0.65,0.75);
			
			// setando a figura que será armazenada em disco
		    bmdDataPicture = new BitmapData(200, 180);
			bmdDataPicture.draw(this.video, matrix);
			
			// setando parâmetro matrix
			var matrixPreview:Matrix = new Matrix();
			matrixPreview.scale(0.6,0.7);
			
			// setando a figura preview
			bmdDataPicturePreview = new BitmapData(this.stageP.stageWidth-280, this.stageP.stageHeight-80);
			bmdDataPicturePreview.draw(this.video, matrixPreview);
			bmdPicturePreview = new Bitmap(bmdDataPicturePreview);	       
			
			return bmdPicturePreview;
		}
		
    }
}
