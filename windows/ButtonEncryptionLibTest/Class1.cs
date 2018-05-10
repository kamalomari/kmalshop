using System;
using System.IO;
using System.Text;


namespace ButtonEncryptionLibTest
{
	/// <summary>
	/// Summary description for Class1.
	/// </summary>
	class Class1
	{
		/// <summary>
		/// The main entry point for the application.
		/// </summary>
		[STAThread]
		static void Main(string[] args)
		{
			if ( args.Length != 5 && args.Length != 6 )
			{
				Console.WriteLine();
				Console.WriteLine("Usage:");
				Console.WriteLine(" PPEncrypt <CertFile> <PrivKeyFile> <PPCertFile> <CmdTxt> <OutputFile> [Sandbox]");
				Console.WriteLine("   CertFile:    Your Public Cert");
				Console.WriteLine("   PrivKeyFile: Your Private Key");
				Console.WriteLine("   PPCertFile:  PayPal's Public Cert");
				Console.WriteLine("   CmdTxt:      The button command, eg: cmd=_xclick,business=...");
				Console.WriteLine("   OutputFile:  File where the html will get written");
				Console.WriteLine("   Sandbox:     Optional. Put 'sandbox' here to test on sandbox accounts, or ");
				Console.WriteLine("                leave blank for testing on live.");
				return;
			}
			
			bool	bVerbose = true;
			string sCertFile = args[0];		
			string sPrivKeyFile = args[1];	
			string sPPCertFile = args[2];	
			string sCmdTxt = args[3];		//	cmd=_xclick,business=sample@sample.com,amount=1.00,currency_code=USD
			string sOutputFile = args[4];	//	test.html
			string sStage = "";
			if ( args.Length == 6 )
				sStage = args[5] + ".";

			string sResult = "";
			string sEnc = new ButtonEncryption(bVerbose).SignAndEncrypt( sCmdTxt, sCertFile, sPrivKeyFile, sPPCertFile, ref sResult );

			Console.WriteLine( sResult );

			if ( sEnc != null && sEnc != "")
			{
				if ( File.Exists( sOutputFile ) )
					File.Delete( sOutputFile );
				StreamWriter OutStream = new StreamWriter(sOutputFile, false, Encoding.ASCII);
				if (OutStream != null) 
				{
					OutStream.Write( @"<form action=""https://www." );
					OutStream.Write( sStage );
					OutStream.WriteLine( @"paypal.com/cgi-bin/webscr"" method=""post"">" );

					OutStream.WriteLine( @"<input type=""hidden"" name=""cmd"" value=""_s-xclick"">" );

					OutStream.Write( @"<input type=""image"" src=""https://www." );
					OutStream.Write( sStage );
					OutStream.WriteLine( @"paypal.com/en_US/i/btn/x-click-but23.gif"" border=""0"" name=""submit"" alt=""Make payments with PayPal - it's fast, free and secure!"">" );

					OutStream.Write( @"<input type=""hidden"" name=""encrypted"" value=""" );
					OutStream.Write( sEnc );
					OutStream.WriteLine( @""">" );

					OutStream.WriteLine( @"</form>" );

					OutStream.Close();
				}
			}
		}
	}
}
