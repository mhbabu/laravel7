<?php

namespace App\Modules\Purchase\Controllers;

use App\DataTables\PurchaseProductDataTable;
use App\Modules\Product\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PurchaseController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PurchaseProductDataTable $dataTable)
    {
        return $dataTable->render("Purchase::index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view("Purchase::create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function productAutoSuggest(Request $request)
    {
        $activeProducts = Product::where(['status'=> 1,'is_archive'=> 0]);
        $term =  $request->input('term');
        if ($term == 'all'){
            $search_items = $activeProducts->orderBy('name', 'desc')->get();
        }else {
            $search_items = $activeProducts->where(function($join) use ($term){
                $join->where('name', 'like', '%' . $term . '%');
                $join->orWhere('product_code', 'like', '%' . $term . '%');
            })->orderBy('name', 'desc')->get(['id', 'name', 'product_code']);
        }

        return response()->json($search_items);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function productAddCart(Request $request){
        try {

            $productId = $request->get('productId');

            $data = Product::where('is_archive', 0)->where('id', $productId) ->first();

            if (!$data)
                return redirect()->back()->with('flash_danger', "This product is not available");

            $product = [];
            $product['id'] = $data->id;
            $product['name'] = $data->name;
            $product['product_code'] = $data->product_code;
            $product['unit'] = $data->unit;
            $product['quantity'] = 1;
            $product['price'] = $data->price;
            $product['item_total'] = $product['quantity'] * $product['price'];
            Session::put("add.activeProduct.$productId", $product);
            $addedProducts = Session::get("add.activeProduct");


            $subtotal = 0;
            $tax = 0;
            $grandTotal = 0;
            $subtotal = 0;
            if(count($addedProducts)>0){
                foreach($addedProducts as $addedProduct){
                    $subtotal += $addedProduct['item_total'];
                    $tax += $addedProduct['item_total'];
                    $tax += $addedProduct['item_total'];
                }
            }

        } catch (\Exception $e) {
            Session::flash('flash_danger', $e->getMessage());
            return redirect()->back();
        } finally {
            return redirect()->back();
        }
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addCartProductDelete(Request $request){
        try {
            $questionId = $request->input('product_id');
            Session::forget("add.activeProduct.$questionId");
        } catch (\Exception $e) {
            Session::flash('flash_danger', $e->getMessage());
            return redirect()->back();
        } finally {
            return redirect()->back();
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeAddQuestion(Request $request)
    {
        $this->validate($request,['year'=>'required']);
        $liveExamId = $request->get('exam_id');
        $decodedLiveExamId = Encryption::decodeId($liveExamId);
        $liveExamQuestions = Session::get("add.activeProduct");

        try {
            DB::beginTransaction();
            foreach ($liveExamQuestions as $key => $liveQuestion) {
                $question = Question::find($liveQuestion['id']);

                $liveExamQuestion                   = new ExamQuestion();
                $liveExamQuestion->exam_id          = $decodedLiveExamId;
                $liveExamQuestion->question_id      = $question->id;
                $liveExamQuestion->name             = $question->name;
                $liveExamQuestion->additional_part  = $question->additional_part;
                $liveExamQuestion->question_type_id = $question->question_type_id;
                $liveExamQuestion->subject_id       = $question->subject_id;
                $liveExamQuestion->year             = $request->get('year');
                $liveExamQuestion->status           = 1;
                $liveExamQuestion->save();

                $examQuestionOptions = QuestionOption::where('question_id', $liveExamQuestion->question_id)
                    ->orderBy('option_no')
                    ->get();

                foreach($examQuestionOptions as $option) {
                    $liveExamQuestionOption                      = new ExamQuestionOption();
                    $liveExamQuestionOption->exam_question_id    = $liveExamQuestion->id;
                    $liveExamQuestionOption->name                = $option->name;
                    $liveExamQuestionOption->additional_part     = $option->additional_part;
                    $liveExamQuestionOption->option_no           = $option->option_no;
                    $liveExamQuestionOption->is_correct_answer   = ($option->is_correct_answer)? 1 : 0;
                    $liveExamQuestionOption->image_path          = $option->image_path;
                    $liveExamQuestionOption->status              = 1;
                    $liveExamQuestionOption->save();
                }
            }

            Session::forget("add.activeProduct");
            DB::commit();
            return redirect(route('exam.live-exams.index'))->with('flash_success', 'Live Exam question added successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('flush_danger', $e->getMessage());
            return redirect()->back();
        }
    }
}
