<?php

namespace App\Presenters;

use Nette,
	App\Model,
	Tracy\Debugger,
	Joseki\Application\Responses\PdfResponse;


class InvoicePresenter extends BasePresenter
{


	/**
	 * @desc Can load data from db, file, or whatever you want.
	 */
	public function actionPdf()
	{
		$template = $this->createTemplate();
		$template->setFile(__DIR__.'/../templates/Invoice/pdfinvoice.latte');
		$template->someValue = 123;
		// Tip: In template to make a new page use <pagebreak>

		$pdf = new PdfResponse($template);

		// optional
		$pdf->documentTitle = date("Y-m-d") . " My super title"; // creates filename 2012-06-30-my-super-title.pdf
		$pdf->pageFormat = 'A4';  // "A4-L" == wide format
		$pdf->getMPDF()->setFooter("|© www.mysite.com|"); // footer

		$this->sendResponse($pdf);
	}



///////component//////////////////////////////////////////////////////



}
