<?php

/**
 * Sort
 *
 * A set of array sorting algorithms
 *
 *      1. Bubble sort          comparison
 *      2. Insertion sort       comparison
 *      3. Selection sort       comparison
 *      4. Quick sort           divide-and-conquer, recursive, comparison
 *      5. Merge sort           divide-and-conquer, recursive, comparison
 *
 * @package Algorithms
 * @version 0.3.0
 * @access  public
 * @see     http://git@github.com/github-34/algo-platter
 * @todo
 *          - ADD: heap sort
 *          - ADD: radix sort
 *          - Add: different qs partitioning: e.g. with middle or random pivot index
 *          - Fix: quick sort (partition) without the edge case??
 */
class Sort
{

    private $algo = 'bubble';

    public function __construct($algo = 'bubble')
    {
        $this->algo = $algo;
    }

    public function getAlgo()
    {
        return $this->algo;
    }

    /**
     * Sort
     *
     * Outputs the unsorted array, sorts it by set algorithm and outputs the sorted array
     *
     * @param   Array   $arr    array - passed by reference
     * @return  void
     * @space           O(1)
     * @time            O(n)
     */
    public function sort(Array &$unsorted)
    {
        // echo $testCaseDescription."\n";
        // $this->printArr($unsorted);

        if ($this->algo === 'bubble')
            $arrSorted = $this->bubbleSort($unsorted);
        else if ($this->algo === 'insertion')
            $arrSorted = $this->insertionSort($unsorted);
        else if ($this->algo === 'selection')
            $arrSorted = $this->selectionSort($unsorted);
        else if ($this->algo === 'quick') {
            $this->quickSort($unsorted, 0, sizeof($unsorted) - 1);
            return $unsorted;
        }
        else if ($this->algo === 'merge') {
            $this->mergeSort($unsorted, 0, sizeof($unsorted) - 1);
            return $unsorted;
        }
        else
            throw new Exception('Sorting algorithm '.$this->algo.' is not implemented.');

        // echo "\n";
        // $this->printArr($arrSorted);
        // echo "\n";

        return $arrSorted;
    }

    /**
     * PrintArr
     *
     * Output the contents of an array
     *
     * @param   Array   $arr    array - passed by reference
     * @return  void
     * @space           O(1)
     * @time            O(n)
     */
    public function printArr(Array &$arr) : void
    {
        echo "[ ";
        for ($i = 0; $i < sizeof($arr); $i++)
            echo $arr[$i] . " ";
        echo "] ";
    }

    /**
     * Swap
     *
     * Swaps the values of two array elements.
     * The array is passed by reference and not returned.
     *
     * Assumption: both array keys exist in the array.
     *
     * @param   array   &$array
     * @param   int     $firstKey       a valid array element index
     * @param   int     $secondKey      a valid array element index
     * @return  void
     * @space           O(1)
     * @time            O(1)
     */
    public function swap(&$array, $firstKey, $secondKey): void
    {
        $temp = $array[$firstKey];
        $array[$firstKey] = $array[$secondKey];
        $array[$secondKey] = $temp;
    }

    /**
     * Bubble Sort
     *
     * Sorts by comparing a pair of elements right next to each other and swapping the larger element toward the end of the array
     * Type: comparison sorting algorithm
     *
     * Pseudo Code:
     *      Iteration: through all elements: result: all elements are sorted.
     *        Iteration: from first element to last unsorted element; result: largest element in unsorted parts gets moved to first element in sorted portion
     *          Comparison: if element is smaller than next-element, swap the two element values
     *
     * @param   array   $arr    an unsorted array of integers
     * @return  array           the sorted array
     * @space                   O(n)
     * @time                    O(n^2)
     */
    public function bubbleSort(Array $arr) : Array
    {
        $lastUnsortedElement = sizeof($arr) - 1;

        // iterate through n array elements
        for ($i = 0; $i < sizeof($arr); $i++) {

            // iterate: move largest element from beginning (by swapping successive pairs) to end of unsorted part of array
            for ($j = 0; $j < $lastUnsortedElement; $j++)
                if ($arr[$j] > $arr[$j + 1])
                    $this->swap($arr, $j, $j + 1);

            // shrink unsorted part of array by one
            $lastUnsortedElement--;
        }
        return $arr;
    }

    /**
     * Insertion Sort
     *
     * Sorts array by inserting an unsorted element into the sorted part of array by comparing each sorted element to it
     * Type: comparison
     *
     * Pseudo Code:
     *      Iterate from last unsorted element to first unsorted element; result: all elements are sorted.
     *          Iterate from first sorted element to last sorted element
     *              if current sorted element value is less than
     *                  swap the current sorted element = the last unsorted element
     *              else (reached element => current sorted element)
     *                  break inner loop
     *
     * @param   array   $arr    an unsorted array of integers
     * @return  array           the sorted array
     * @space                   O(n)
     * @time                    O(n^2)
     */
    public function insertionSort(Array $arr) : Array
    {
        if (empty($arr) || sizeof($arr) === 1)
            return $arr;

        $lue = sizeof($arr) - 2;                                // index of last unsorted element

        for ($i = $lue; $i >= 0; $i--) {

            for ($j = $i; $j <= sizeof($arr) - 2; $j++) {       // j is current sorted element were comparing i (last unsorted element) to
                if ($arr[$j] > $arr[$j + 1])
                    $this->swap($arr, $j, $j + 1);
                else
                    break;
            }
        }

        return $arr;
    }

    /**
     * Selection Sort
     *
     * Sorts an array by finding the smallest element in unsorted part of the array and swapping
     * it with the first element in unsorted part of array.
     * Type: basic comparison
     *
     * Pseudo-code:
     *      Iterate through array
     *          Iterate through all element of unsorted part of array
     *              Search for smallest element in unsorted portion
     *          Swap smallest with first unsorted element
     *          Update first unsorted element as the smallest
     *
     * @param   array   $arr    an unsorted array of integers
     * @return  array           a sorted array
     * @space                   O(n)
     * @time                    O(n^2)
     */
    public function selectionSort(Array $arr) : Array
    {
        $fue = 0;                   // first unsorted element
        $smallest = 0;              // index of smallest element in current unsorted search/iteration
        $last = sizeof($arr) - 1;

        // iterate through entire array
        for ($i = 0; $i <= $last; $i++) {

            // iterate through unsorted part of array
            for ($j = $fue; $j <= $last; $j++) {

                // find smallest unsorted element
                if ($arr[$j] < $arr[$smallest])
                    $smallest = $j;
            }

            // swap smallest unsorted element with first unsorted element
            $this->swap($arr, $smallest, $fue);

            $fue++;
            $smallest = $fue;
        }

        return $arr;
    }

    /**
     * Quick Sort
     *
     * Sorts array elements by recursively partitioning parts of array. Partitioning sorts elements less than the pivot into the partition to
     * the left of the pivot and elements greater into the partition to the right of the pivot. Partitioning a segment of the array, indicated
     * by a start-end indices, does not ensure that each partition is sorted.
     *
     * Implementation:
     *  1. Partitioning is done by comparing values of a comparisonIndex and pivotIndex, moving the pivot index (from end to )
     *  2. Quick sort variants can be implemented with different pivot choices. In this case, the pivot is always chosen to be the value at the
     *     end of the array we are starting to partition.
     *
     * @param   array   $arr
     * @param   int     $startIndex
     * @param   int     $endIndex
     * @return  void
     * @space           O(n)
     * @time            average: O(nlogn); best case: O(nlogn) ; worse case: O(n^2), when array is sorted.
     */
    public function quickSort(&$arr, $startIndex, $endIndex) : void {

        $numElements = ($endIndex - $startIndex) + 1;
        if ($numElements <= 1)
            return;

        $newPivotIndex = $this->partition($arr, $startIndex, $endIndex);
        $this->quickSort($arr, $startIndex, $newPivotIndex - 1 );
        $this->quickSort($arr, ($newPivotIndex + 1), $endIndex );
    }

    /**
     * Partition
     *
     * Sort elements into left-right partitions relative to pivot, where all elements in left partition are less than pivot and all elements
     * in right partition are greater than pivot. Pivot is, initially, assumed to be the value of endIndex.
     *
     * Pseudo-code
     *      pivotIndex = last element index
     *      comparisonIndex = 0
     *      iterate while comparisonIndex < pivotIndex
     *          Comparison:
     *              element@comparisonIndex >= element@pivotIndex
     *                  3-swap (pivot, element prior to pivot, comparison element):
     *                      pivot => prior to pivot
     *                      prior to pivot => comparison
     *                      comparison => pivot
     *                  move pivotIndex down one
     *              element@comparisonIndex < element@pivotIndex
     *                  move comparisonIndex up one
     *
     * @param   array   $arr
     * @param   int     $startIndex
     * @param   int     $endIndex
     * @return  int                     the new pivot value index
     * @space           O(n)
     * @time            O(n)
     */
    public function partition(&$arr, $startIndex, $endIndex) : int {

        if (empty($arr))
            throw new Exception("Array is empty. Cannot partition empty array.");

        $pivotIndex = $endIndex;
        $pivotValue = $arr[$pivotIndex];
        $cmpIndex = $startIndex;

        while ($cmpIndex < $pivotIndex)
        {
            if ($arr[$cmpIndex] >= $arr[$pivotIndex])
            {
                // Edge Case: cmpIndex and Pivot Index right next to each other: do two element swap
                if (($pivotIndex - $cmpIndex) === 1)
                    $this->swap($arr, $cmpIndex, $pivotIndex);
                // cmptIndex and pivotIndex gap 3 elements or more: do three element swap
                else {
                    $priorVal = $arr[$pivotIndex - 1];
                    $arr[$pivotIndex - 1] = $arr[$pivotIndex];      // move the pivot down one
                    $arr[$pivotIndex] = $arr[$cmpIndex];            // put the greater value where the old pivot was
                    $arr[$cmpIndex] = $priorVal;                    // the prior value put into cmpIndex
                }
                $pivotIndex--;
            }
            else
                $cmpIndex++;
        }

        return $pivotIndex;
    }

    /**
     * Merge Sort
     *
     * Sorts an array by merging sorted partitions
     *
     * @param   array   $arr
     * @return  void            $arr is sorted as a side effect
     */
    public function mergeSort($arr, $start, $end )
    {

        if ($end - $start <= 1)
            return $arr;

        $numElements = ($end - $start) + 1;
        $midpoint = floor($numElements / 2);
        // 2 => 1
        // 3 => 1

        echo "s:".$start."-e:".$end."-m:".$midpoint."\n";
        $sorted1 = $this->mergeSort($arr, $start, ($start + $midpoint));
        print_r($sorted1);
        $sorted2 = $this->mergeSort($arr, ($start + $midpoint + 1), $end);
        $merged = $this->merge($sorted1, $sorted2);
        return $merged;
    }

    public function merge(&$arr1, &$arr2) : array
    {
        $lSmallest = 0; 
        $rSmallest = 0;
        $lpEI = sizeof($arr1) - 1;
        $rpEI = sizeof($arr2) - 1;


        $merged = [];

        // nothing to merge: 0 or 1 element in both partitions
        //if (empty($arr)) return [];
        //if (($rpEI - $lpEI) === 0) return [ $arr[$rpEI] ];

        // loop, adding smallest element to merge, until one (or both) of the partitions is done
        while ( ($lSmallest <= $lpEI) && ($rSmallest <= $rpEI )
            && $lpEI >= 0 && $rpEI >= 0)        // arr1 is not empty and arr2 is not empty
        {
            if ($arr1[$lSmallest] < $arr2[$rSmallest])
            {
                array_push($merged, $arr1[$lSmallest]);
                $lSmallest++;
            }
            elseif ($arr1[$lSmallest] > $arr2[$rSmallest])
            {
                array_push($merged, $arr2[$rSmallest]);
                $rSmallest++;
            }
            else //if ($arr[$lSmallest] === $arr[$rSmallest])
            {
                array_push($merged, $arr1[$lSmallest]);
                array_push($merged, $arr2[$rSmallest]);
                $lSmallest++;
                $rSmallest++;
            }
        }

        // add to merge the elements of remaining (unfinished) partition: where rSmallest < rpEI or lSmallest  < lpSI
        if ($lSmallest > $lpEI && $rSmallest > $rpEI)
            return $merged;

        if ($lSmallest > $lpEI)
            for ($i = $rSmallest; $i <= $rpEI; $i++)
                array_push($merged, $arr2[$i]);
        elseif ($rSmallest > $rpEI)
            for ($i = $lSmallest; $i <= $lpEI; $i++)
                array_push($merged, $arr1[$i]);

        return $merged;
    }

    /**
     * Merge
     *
     * Merges two sorted array partitions (right next to each other) identified by start-end indexes
     *
     * Assumption: indexes arguments are ordered correctly
     *      $lpSI <= 0
     *      $lpSI <= $lpEI
     *      $lpEI < $rpSI  ( $rpSI = $lpEI + 1?????)
     *      $rpSI <= $rpEI
     *      $rpEI < sizeof($arr)
     *      lpSi / rpSi are next to each other???????
     *
     * @param   array   $arr
     * @param   int     $lpSI   left partition start index
     * @param   int     $lpEI   left partition end index
     * @param   int     $rpSI   right partition start index
     * @param   int     $rpEI   right partition end index
     * @return  array           arr with elements from left partition and right partition sorted
     * @space           O?
     * @time            O?
     */
    public function merge2(&$arr, $lpSI, $lpEI, $rpSI, $rpEI) : array
    {
        $lSmallest = $lpSI;
        $rSmallest = $rpSI;

        $merged = [];

        // nothing to merge: 0 or 1 element in both partitions
        if (empty($arr)) return [];
        if (($rpEI - $lpEI) === 0) return [ $arr[$rpEI] ];

        // loop, adding smallest element to merge, until one (or both) of the partitions is done
        while ( ($lSmallest <= $lpEI) && ($rSmallest <= $rpEI ))
        {
            if ($arr[$lSmallest] < $arr[$rSmallest])
            {
                array_push($merged, $arr[$lSmallest]);
                $lSmallest++;
            }
            elseif ($arr[$rSmallest] < $arr[$lSmallest])
            {
                array_push($merged, $arr[$rSmallest]);
                $rSmallest++;
            }
            else //if ($arr[$lSmallest] === $arr[$rSmallest])
            {
                array_push($merged, $arr[$lSmallest]);
                array_push($merged, $arr[$rSmallest]);
                $lSmallest++;
                $rSmallest++;
            }
        }

        // add to merge the elements of remaining (unfinished) partition: where rSmallest < rpEI or lSmallest  < lpSI
        if ($lSmallest > $lpEI && $rSmallest > $rpEI)
            return $merged;

        if ($lSmallest > $lpEI)
            for ($i = $rSmallest; $i <= $rpEI; $i++)
                array_push($merged, $arr[$i]);
        elseif ($rSmallest > $rpEI)
            for ($i = $lSmallest; $i <= $lpEI; $i++)
                array_push($merged, $arr[$i]);

        return $merged;
    }
}
/**
 * Test for arrays with two, side-by-side, sorted partitions
 */

//  // TEST FOR MERGE2 (from 1 array, with two sorted partitions by indexes)
// $tests = [
//     [ [], [], 0,0,0,0 ],
//     [ [ 1 ], [ 1 ], 0,0,0,0],
//     [[ 1, 1], [ 1, 1], 0,0, 1, 1],
//     [[ 1, 2], [ 1, 2], 0,0, 1, 1],
//     [[ 3, 4, 6, 2, 4, 5 ], [ 2, 3, 4, 4, 5, 6], 0, 2, 3, 5 ],
//     [[ 3, 4, 6, 1, 2, 5 ], [ 1, 2, 3, 4, 5, 6], 0, 2, 3, 5 ],
//     [[ 1, 2, 9, 9, 1, 2, 9 ], [ 1, 1, 2, 2, 9, 9, 9], 0, 3, 4, 6 ],
//     [[ 1, 1, 1, 1, 1, 9, 9, 9 ], [ 1, 1, 1, 1, 1, 9, 9, 9 ], 0, 4, 5, 7 ],
//     [[ 1, 1, 1, 9, 9, 9, 9, 9 ], [ 1, 1, 1, 9, 9, 9, 9, 9 ], 0, 2, 3, 7 ]
// ];

// // // TEST for MERGE (from 2 sorted array returns Array)
// $tests = [
//     [ [], [] ],
//     [ [ 1 ], [ ] ],
//     [ [ 1 ], [ 1 ] ],
//     [ [ 1,1 ], [ 1 ] ],
//     [ [ 1,1 ], [ 1, 1 ] ],
//     [ [ 1, 2], [ 1, 2]],
//     [ [ 3, 4, 6, 9, 11 ], [ 2, 3, 4, 5, 6]],
//     [[ 100, 101, 200 ], [ 1, 2, 3, 4, 5, 6] ],
//     [[ 9, 9, 9, 100, 200 ], [ 1, 1, 2, 2, 9, 9, 9] ],
// //     // [[ 1, 1, 1, 1, 1, 9, 9, 9 ], [ 1, 1, 1, 1, 1, 9, 9, 9 ], 0, 4, 5, 7 ],
// //     // [[ 1, 1, 1, 9, 9, 9, 9, 9 ], [ 1, 1, 1, 9, 9, 9, 9, 9 ], 0, 2, 3, 7 ]
// ];
// $sorter = new Sort('merge');
// foreach ($tests as $test)
// {
//     echo "\n";
//     $sorter->printArr($test[0]);
//     $sorter->printArr($test[1]);
//     echo "\n";
//     $sorted = $sorter->merge($test[0], $test[1]); //, $test[3], $test[4], $test[5]);
//     $sorter->printArr($sorted);
//     echo "\n";
// }

// $unsortedArrs = [
//     [1],
//     [1,2],
//     [2,1],
//     [2,2,1],
//     [2,2,1,1],
//     [1, 4, 3, 6 ],
//     [1,2,1],
//     [2,5, 6, 1],
//     [ 2, 6, 1, 94, 5, 9],
//     [ 2, 6, 1, 94, 5, 9],
//     [ 2, 6, 1, 94, 5, 4, 7],
//     [ 2, 6, 6, 1, 94, 5, 5, 2, 7],
//     [],
// ];

// $sorter = new Sort('merge');
// foreach ($unsortedArrs as $arr) {
//     echo "\n\nU: ";
//     $sorter->printArr($arr);
//     echo "\n";
//     //$sorter->partition($arr,0, sizeof($arr) - 1);
//     $sorter->sort($arr);
//     echo "S: ";
//     $sorter->printArr($arr);
// }
// echo "\n";

?>
